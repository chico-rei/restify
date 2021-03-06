<?php namespace ChicoRei\Packages\Restify\Http\Controllers;

use Exception;
use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use ChicoRei\Packages\Restify\Exceptions\ResourceException;
use ChicoRei\Packages\Restify\Factories\ResponseFactory;
use ChicoRei\Packages\Restify\Factories\ValidationRulesFactory;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class BaseController
 *
 * @package ChicoRei\Packages\Restify\Http\Controllers
 */
class BaseController extends Controller
{
    //<editor-fold desc="Fields">
    /**
     * @var array
     */
    protected $prefix;

    /**
     * @var string
     */
    protected $pluralizedModels;

    /**
     * @var string
     */
    protected $pluralizedRoutes;

    /**
     * @var ValidationRulesFactory
     */
    protected $validationRulesFactory;

    /**
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * @var mixed
     */
    protected $routePermissionEnabled;

    /**
     * @var mixed
     */
    protected $publicRoutes;

    /**
     * @var string
     */
    protected $failedValidationMessage;

    /**
     * @var boolean
     */
    protected $exceptionHandling;

    /**
     * @var Router
     */
    private $router;
    //</editor-fold>

    //<editor-fold desc="Constructor">
    /**
     * @param Config $config
     * @param Router $router
     * @param ValidationRulesFactory $validationRulesFactory
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Config $config, Router $router, ValidationRulesFactory $validationRulesFactory, ResponseFactory $responseFactory)
    {
        $this->prefix = (array) $config->get('restify.prefix');
        $this->pluralizedModels = $config->get('restify.pluralized_models');
        $this->pluralizedRoutes = $config->get('restify.pluralized_routes');
        $this->failedValidationMessage = $config->get('restify.failed_validation_message');
        $this->exceptionHandling = $config->get('restify.exception_handling', true);

        $this->validationRulesFactory = $validationRulesFactory;
        $this->responseFactory = $responseFactory;
        $this->router = $router;
    }
    //</editor-fold>

    //<editor-fold desc="Model">
    /**
     * Split URL
     *
     * @return array
     */
    private function currentPathComponents()
    {
        // Retrieve current route
        $route = $this->router->current()->uri();

        // Remove prefix from url path
        foreach ($this->prefix as $prefix) {
            if (mb_strpos($route, rtrim($prefix, '/').'/') !== false) {
                $route = str_replace($prefix . '/', '', $route);
                break;
            }
        }

        return explode('/', $route);
    }

    /**
     * Gets the respective model name.
     *
     * @return mixed Model name.
     */
    protected function resourceClassName()
    {
        $pathComponents = $this->currentPathComponents();

        // Translates first element of the path to class name
        return $this->toClassName($pathComponents[0]);
    }

    /**
     * Gets the respective nested model name.
     *
     * @return mixed Nested model name.
     */
    protected function nestedResourceClassName()
    {
        $pathComponents = $this->currentPathComponents();

        // Translates last element of the path to class name
        return $this->toClassName($pathComponents[2]);
    }

    /**
     * Convert string to class name. First it should convert to studly caps and then return the singular format
     * if the package is configured to use pluralized routes and models are not pluralized.
     *
     * @param string $value
     * @return string Respective class name
     */
    protected function toClassName($value)
    {
        $studly = Str::studly($value);

        return $this->pluralizedRoutes && !$this->pluralizedModels ? Str::singular($studly) : $studly;
    }
    //</editor-fold>

    //<editor-fold desc="Helpers">
    /**
     * Executes command and returns the response created by the response factory.
     *
     * @param mixed $command
     * @return mixed|HttpResponse
     */
    protected function dispatch($command)
    {
        try
        {
            $data = parent::dispatch($command);

            $transformer = Arr::get($this->router->current()->defaults, 'restify.transformer');

            if (is_string($transformer) && class_exists($transformer)) {
                $transformer = new $transformer;
            }

            return $this->responseFactory->create($data, null, $transformer);
        } catch (Exception $e)
        {
            app(ExceptionHandler::class)->report($e);

            return $this->renderException($e);
        }
    }

    /**
     * @param Exception $e
     * @return mixed
     */
    protected function renderException($e)
    {
        return $this->exceptionHandling
            ? $this->responseFactory->create($e)
            : app(ExceptionHandler::class)->render(request(), $e);
    }

    /**
     * Throw the failed validation exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws ResourceException
     */
    protected function throwValidationException(Request $request, $validator)
    {
        throw new ResourceException($this->failedValidationMessage, $validator->getMessageBag());
    }
    //</editor-fold>
}
