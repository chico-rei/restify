<?php namespace ChicoRei\Packages\Restify\Transformers;

use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class HttpExceptionTransformer
 *
 * @package ChicoRei\Packages\Restify\Transformers
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
