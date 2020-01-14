<?php namespace ChicoRei\Packages\Restify\Services\Validation;

/**
 * Class Validator
 *
 * @package ChicoRei\Packages\Restify\Services\Validation
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
