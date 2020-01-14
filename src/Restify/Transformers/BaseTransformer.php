<?php namespace ChicoRei\Packages\Restify\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

/**
 * Class BaseTransformer
 *
 * @package ChicoRei\Packages\Restify\Transformers
 */
class BaseTransformer extends TransformerAbstract
{
    /**
     * @param Model $object
     * @return array
     */
    public function transform($object)
    {
        return $object->toArray();
    }
}
