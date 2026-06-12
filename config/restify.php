<?php

use League\Fractal\Serializer\ArraySerializer;

return [

    /*
    |--------------------------------------------------------------------------
    | Route Prefixes
    |--------------------------------------------------------------------------
    |
    | Prefixes that are stripped from the request URI when resolving the
    | target model/command. May be a single string or a list of prefixes.
    | Read by BaseController.
    |
    */

    'prefix' => [
        'api',
    ],

    /*
    |--------------------------------------------------------------------------
    | Base Paths
    |--------------------------------------------------------------------------
    |
    | Class name templates used to resolve the model, validator, command and
    | transformer for a given resource. The placeholders {model}, {nestedModel}
    | and {prefix} are replaced at runtime.
    |
    */

    'paths' => [
        'models' => 'App\\Models\\{model}',
        'validators' => 'App\\Validators\\{prefix}{model}Validator',
        'commands' => 'App\\Jobs\\{model}\\{prefix}{model}{nestedModel}Command',
        'transformers' => 'App\\Transformers\\{model}Transformer',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pluralization Flags
    |--------------------------------------------------------------------------
    |
    | When truthy, model/route names are pluralized/singularized via Str when
    | resolving routes (BaseController) and nested models (BaseCommand).
    |
    */

    'pluralized_models' => false,
    'pluralized_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    |
    | Default message used when building a validation exception.
    |
    */

    'failed_validation_message' => 'Falha na validação',

    /*
    |--------------------------------------------------------------------------
    | Exception Handling
    |--------------------------------------------------------------------------
    |
    | When enabled, BaseController catches exceptions thrown while dispatching
    | a command and renders them through the response factory. Defaults to true.
    |
    */

    'exception_handling' => true,

    /*
    |--------------------------------------------------------------------------
    | Fractal Serializer
    |--------------------------------------------------------------------------
    |
    | The serializer used by the ResponseFactory to format API responses.
    | You may provide any class that extends
    | League\Fractal\Serializer\SerializerAbstract, or a closure / callable
    | that returns such an instance. Defaults to Fractal's ArraySerializer.
    |
    */

    'serializer' => ArraySerializer::class,

];
