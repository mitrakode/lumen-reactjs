<?php

/** @var \Dingo\Api\Routing\Router $api */
$api = app('Dingo\Api\Routing\Router');
$api->version('v1',
    [
        'namespace' => 'App\Http\Controllers\Api\V1'
    ],
    function (\Dingo\Api\Routing\Router $api) {
        $api->get('/', [
            'as' => 'home.index',
            'uses' => 'HomeController@index',
        ]);
    });
