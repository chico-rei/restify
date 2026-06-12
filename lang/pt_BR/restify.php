<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mensagens de Erro
    |--------------------------------------------------------------------------
    |
    | Lançadas pelos comandos de recurso. Placeholders disponíveis:
    |   not_found -> :model
    |   delete    -> :model, :id
    |
    */

    'errors' => [
        'not_found' => ':model não encontrado.',
        'delete' => 'Não foi possível remover :model #:id.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Nomes de Exibição dos Models
    |--------------------------------------------------------------------------
    |
    | Mapeia o nome da classe do model para um nome amigável usado nas
    | mensagens de erro acima. As chaves são os nomes das classes, ex.:
    |
    |   'User' => 'usuário',
    |   'Order' => 'pedido',
    |
    */

    'display_name' => [
        //
    ],

];
