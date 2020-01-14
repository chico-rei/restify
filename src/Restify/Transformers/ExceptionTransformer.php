<?php namespace ChicoRei\Packages\Restify\Transformers;

use Exception;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Class ExceptionTransformer
 *
 * @package ChicoRei\Packages\Restify\Transformers
 */
class ExceptionTransformer extends TransformerAbstract
{
    /**
     * @param Exception $exception
     * @return array
     */
    public function transform(Exception $exception)
    {
        return [
            'status_code' => HttpResponse::HTTP_INTERNAL_SERVER_ERROR,
            'message' => $exception->getMessage()
        ];
    }
}
