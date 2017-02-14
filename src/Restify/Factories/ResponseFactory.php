<?php namespace ChicoRei\Packages\Restify\Factories;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use League\Fractal;
use League\Fractal\Serializer\ArraySerializer;
use ChicoRei\Packages\Restify\Exceptions\ResourceException;
use ChicoRei\Packages\Restify\Transformers\BaseTransformer;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 5/4/15
 * Time: 9:20 PM
 */
class ResponseFactory
{
    //<editor-fold desc="Fields">
    /**
     * @var Fractal\Manager
     */
    private $fractal;
    /**
     * @var int
     */
    private $statusCode;
    /**
     * @var mixed
     */
    private $content;
    /**
     * @var bool
     */
    private $appendStatusCode;
    //</editor-fold>

    //<editor-fold desc="Constructor">
    /**
     * ResponseFactory constructor.
     */
    public function __construct()
    {
        $this->fractal = new Fractal\Manager();
        $this->fractal->setSerializer(new ArraySerializer());
    }
    //</editor-fold>

    //<editor-fold desc="Factory">
    /**
     * Create a response instance from the given parameters.
     *
     * @param mixed $data
     * @param int $status
     * @param BaseTransformer $transformer
     * @return mixed
     */
    public function create($data = [], $status = null, $transformer = null)
    {
        $this->setContent($data);

        if (!isset($data))
        {
            $this->setContent('')->setStatusCode(HttpResponse::HTTP_NO_CONTENT);
        }
        elseif ($data instanceof LengthAwarePaginator)
        {
            $this->setContent($this->handlePagination($data, $transformer));
        }
        elseif ($data instanceof Collection)
        {
            $this->setContent($this->handleCollection($data, $transformer));
        }
        elseif ($data instanceof Model)
        {
            $this->setContent($this->handleItem($data, $transformer));
        }
        elseif ($data instanceof ResourceException || get_parent_class($data) == ResourceException::class)
        {
            $this->setContent($this->handleError($data, $transformer, ResourceException::class))->setStatusCode($data->getStatusCode());
        }
        elseif ($data instanceof HttpException || get_parent_class($data) == HttpException::class)
        {
            $this->setContent($this->handleError($data, $transformer, HttpException::class))->setStatusCode($data->getStatusCode());
        }
        elseif ($data instanceof ValidationException || get_parent_class($data) == ValidationException::class)
        {
            // Create new ResourceException with the given errors if it is a ValidationException
            $resourceException = new ResourceException($data->getMessage(), $data->validator->errors());

            $this->setContent($this->handleError($resourceException, $transformer))->setStatusCode($resourceException->getStatusCode());
        }
        elseif ($data instanceof Exception || get_parent_class($data) == Exception::class)
        {
            $this->setContent($this->handleError($data, $transformer, Exception::class))->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
        elseif ($data instanceof FatalErrorException)
        {
            $this->setContent($this->handleError($data, $transformer, Exception::class))->setStatusCode(HttpResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Override status code
        if (isset($status)) $this->setStatusCode($status);

        return response()->json($this->getContent(), $this->getStatusCode());
    }
    //</editor-fold>

    //<editor-fold desc="Getters and Setters">
    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode ?: HttpResponse::HTTP_OK;
    }

    /**
     * @param int $statusCode
     * @param bool $append
     * @return $this
     */
    public function setStatusCode($statusCode, $append = false)
    {
        $this->statusCode = $statusCode;
        $this->appendStatusCode = $append;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        if ($this->appendStatusCode)
        {
            return array_merge(['status_code' => $this->getStatusCode()], $this->content);
        }

        return $this->content;
    }

    /**
     * @param mixed $content
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    //</editor-fold>

    //<editor-fold desc="Transformation">
    /**
     * Create array from a fractal resource, parsing required includes.
     *
     * @param Fractal\Resource\ResourceAbstract $resource
     * @return array
     */
    private function handleResource(Fractal\Resource\ResourceAbstract $resource)
    {
        return $this->fractal
            ->parseIncludes(request('include', []))
            ->createData($resource)
            ->toArray();
    }

    /**
     * @param LengthAwarePaginator $pagination
     * @param null|Fractal\TransformerAbstract $transformer
     * @return array
     */
    private function handlePagination(LengthAwarePaginator $pagination, $transformer = null)
    {
        $transformer = $transformer ?: TransformerFactory::create(class_basename($pagination->getCollection()->first()));

        $resource = new Fractal\Resource\Collection($pagination->getCollection(), $transformer);
        $resource->setPaginator(new Fractal\Pagination\IlluminatePaginatorAdapter($pagination));

        return $this->handleResource($resource);
    }

    /**
     * @param Collection $collection Collection that will be transformed
     * @param null|Fractal\TransformerAbstract $transformer
     * @return array
     */
    private function handleCollection(Collection $collection, $transformer = null)
    {
        $transformer = $transformer ?: TransformerFactory::create(class_basename($collection->first()));

        return $this->handleResource(new Fractal\Resource\Collection($collection, $transformer));
    }

    /**
     * @param mixed $item Object that will be transformed
     * @param null|Fractal\TransformerAbstract $transformer
     * @return array Transformed data
     */
    private function handleItem($item, $transformer = null)
    {
        $transformer = $transformer ?: TransformerFactory::create(class_basename($item));

        return $this->handleResource(new Fractal\Resource\Item($item, $transformer));
    }

    /**
     * @param mixed $item Object that will be transformed
     * @param null|Fractal\TransformerAbstract $transformer
     * @param null|string $baseClassPath Error class base name should be used when the class
     * does not have a specific transformer and you want to use a custom one.
     * @return array Transformed data
     */
    private function handleError($item, $transformer = null, $baseClassPath = null)
    {
        $transformer = $transformer ?: TransformerFactory::create(class_basename($baseClassPath ?: $item));

        return $this->handleResource(new Fractal\Resource\Item($item, $transformer));
    }
    //</editor-fold>
}
