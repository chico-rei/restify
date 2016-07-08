<?php namespace Restify\Transformers;

use Illuminate\Database\Eloquent\Model;
use League\Fractal\TransformerAbstract;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 5/4/15
 * Time: 9:12 PM
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
