<?php

use League\Fractal\Serializer\ArraySerializer;

return [

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
