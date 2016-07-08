<?php namespace Restify\Services\Validation;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 5/20/15
 * Time: 9:19 PM
 */
abstract class Validator
{
    /**
     * Get the validation rules.
     *
     * @return array
     */
    public abstract function rules();
}
