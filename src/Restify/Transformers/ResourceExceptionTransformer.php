<?php namespace ChicoRei\Packages\Restify\Transformers;

use League\Fractal\TransformerAbstract;
use ChicoRei\Packages\Restify\Exceptions\ResourceException;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 5/4/15
 * Time: 9:12 PM
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
