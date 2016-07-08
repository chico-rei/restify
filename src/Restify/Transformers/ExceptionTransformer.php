<?php namespace Restify\Transformers;

use Exception;
use League\Fractal\TransformerAbstract;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 5/4/15
 * Time: 9:12 PM
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
