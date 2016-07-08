<?php namespace Restify\Factories;

use Restify\Transformers\BaseTransformer;

/**
 * Created by PhpStorm.
 * User: bendia
 * Date: 5/4/15
 * Time: 9:20 PM
 */
class TransformerFactory
{
    /**
     * Create a transformer instance from a model class name.
     *
     * @param string $modelClass
     * @return BaseTransformer
     */
    public static function create($modelClass)
    {
        $transformerClass = static::modelTransformerClass($modelClass);

        if (class_exists($transformerClass)) return new $transformerClass();

        $transformerClass = static::restifyTransformerClass($modelClass);

        return new $transformerClass();
    }

    /**
     * @param string $modelClass
     * @return string
     */
    private static function modelTransformerClass($modelClass)
    {
        $path = config('restify.paths.transformers');

        return str_replace('{model}', $modelClass, $path);
    }

    /**
     * @param string $className
     * @return string
     */
    private static function restifyTransformerClass($className)
    {
        $classPath = 'Restify\\Transformers\\' . $className . 'Transformer';

        return class_exists($classPath) ? $classPath : BaseTransformer::class;
    }
}
