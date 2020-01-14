<?php namespace ChicoRei\Packages\Restify\Transformers;

use League\Fractal\TransformerAbstract;
use ChicoRei\Packages\Restify\Exceptions\ResourceException;

/**
 * Class ResourceExceptionTransformer
 *
 * @package ChicoRei\Packages\Restify\Transformers
 */
class ResourceExceptionTransformer extends TransformerAbstract
{
    /**
     * @param ResourceException $exception
     * @return array
     */
    public function transform(ResourceException $exception)
    {
        return [
            'status_code' => $exception->getStatusCode(),
            'message' => $exception->getMessage(),
            'errors' => $exception->getErrors(),
        ];
    }
}
