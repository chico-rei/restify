<?php namespace Restify\Factories;

use League\Fractal;
use Restify\Services\Validation\Validator;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 5/20/15
 * Time: 9:20 PM
 */
class ValidationRulesFactory
{
    /** @var array Mapeamento entre o nome da action e o nome do comando */
    private static $actionCommandMapping = [
        'index' => 'List',
        'store' => 'Create',
        'show' => 'Read',
        'update' => 'Update',
        'destroy' => 'Delete',
    ];

    /**
     * @param string $modelName
     * @param string $action
     * @return array
     */
    public function create($modelName, $action)
    {
        $classPrefix = $this->validatorPrefix($action);
        $validator = $this->validator($modelName, $classPrefix);

        return isset($validator) ? $validator->rules() : [];
    }

    /**
     * @param string $action
     * @return string
     */
    private static function validatorPrefix($action)
    {
        if (array_key_exists($action, static::$actionCommandMapping))
        {
            return static::$actionCommandMapping[$action];
        }

        return '';
    }

    /**
     * @param string $modelClass
     * @param string $classPrefix
     * @return string
     */
    private static function validatorClass($modelClass, $classPrefix)
    {
        $path = config('restify.paths.validators');

        $path = str_replace('{model}', $modelClass, $path);
        $path = str_replace('{prefix}', $classPrefix, $path);

        return $path;
    }

    /**
     * @param string $modelClass
     * @param string $classPrefix
     * @return Validator|null
     */
    private static function validator($modelClass, $classPrefix)
    {
        $validatorClass = static::validatorClass($modelClass, $classPrefix);

        return class_exists($validatorClass) ? new $validatorClass() : null;
    }
}
