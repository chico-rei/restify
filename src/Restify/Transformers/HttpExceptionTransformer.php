<?php namespace ChicoRei\Packages\Restify\Transformers;

use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 5/4/15
 * Time: 9:12 PM
 */
class HttpExceptionTransformer extends TransformerAbstract
{
    /**
     * @param HttpException $exception
     * @return array
     */
    public function transform(HttpException $exception)
    {
        return [
            'status_code' => $exception->getStatusCode(),
            'message' => $exception->getMessage(),
        ];
    }
}
