<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Error Messages
    |--------------------------------------------------------------------------
    |
    | Thrown by the resource commands. Available placeholders:
    |   not_found -> :model
    |   delete    -> :model, :id
    |
    */

    'errors' => [
        'not_found' => ':model not found.',
        'delete' => 'Could not delete :model #:id.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Model Display Names
    |--------------------------------------------------------------------------
    |
    | Maps a model class basename to a human-friendly name used in the error
    | messages above. Keys are the model class names, e.g.
    |
    |   'User' => 'user',
    |   'Order' => 'order',
    |
    */

    'display_name' => [
        //
    ],

];
