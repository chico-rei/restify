<?php namespace ChicoRei\Packages\Restify\Http\Controllers;

use Exception;
use Illuminate\Config\Repository as Config;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Log;
use ChicoRei\Packages\Restify\Exceptions\ResourceException;
use ChicoRei\Packages\Restify\Factories\ResponseFactory;
use ChicoRei\Packages\Restify\Factories\ValidationRulesFactory;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 4/26/15
 * Time: 8:59 PM
 */
class BaseController extends Controller
{
    //<editor-fold desc="Fields">
    /**
     * @var string
     */
    protected $prefix;

    /**
     * @var bool
     */
    protected $jwtEnabled;

    /**
     * @var bool
     */
    protected $jwtRefreshToken;

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
    //</editor-fold>

    //<editor-fold desc="Constructor">
    /**
     * @param Config $config
     * @param ValidationRulesFactory $validationRulesFactory
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Config $config, ValidationRulesFactory $validationRulesFactory, ResponseFactory $responseFactory)
    {
        $this->prefix = $config->get('restify.prefix');
        $this->jwtEnabled = $config->get('restify.jwt.enabled', false);
        $this->jwtRefreshToken = $config->get('restify.jwt.refresh_token', false);
        $this->publicRoutes = $config->get('restify.jwt.public_routes', []);
        $this->failedValidationMessage = $config->get('restify.failed_validation_message');

        $this->validationRulesFactory = $validationRulesFactory;
        $this->responseFactory = $responseFactory;

        $url = $this->getRouter()->current();

        // Add filters if the current route is a public route.
        if ($this->jwtEnabled && $url && !in_array($url->getPath(), $this->publicRoutes))
        {
            $this->middleware('jwt.auth');

            if ($this->jwtRefreshToken) $this->middleware('jwt.refresh');
        }
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
        $route = $this->getRouter()->current()->getPath();

        // Remove prefix from url path
        return explode('/', str_replace($this->prefix . '/', '', $route));
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
        return Str::studly(Str::singular($pathComponents[0]));
    }

    /**
     * Gets the respective nested model name.
     *
     * @return mixed Nested model name.
     */
    protected function nestedResourceClassName()
    {
        $pathComponents = $this->currentPathComponents();

        // Translates first element of the path to class name
        return Str::studly(Str::singular($pathComponents[2]));
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

            return $this->responseFactory->create($data);
        } catch (Exception $e)
        {
            Log::debug($e->getMessage() . ': ' . $e->getTraceAsString());

            return $this->responseFactory->create($e);
        }
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
