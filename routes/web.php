<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'user'], function() use($router){
    $router->post('/', 'UserController@store');
    $router->post('/{id}', 'UserController@edit');
    $router->get('/', 'MobileController@getAvailable');
    $router->get('/{id}', 'UserController@find');
    $router->delete('/{id}', 'UserController@destroy');
});

$router->group(['prefix' => 'responsiva'], function() use($router){
    $router->post('/', 'ResponsivaController@createPdf');
});

$router->group(['prefix' => 'mobile'], function() use($router){
    $router->post('/', 'MobileController@store');
    $router->post('/{id}/history', 'MobileController@createHistory');
    $router->post('/{id}', 'MobileController@edit');
    $router->delete('/{id}', 'MobileController@destroy');
    $router->get('/quantity', 'MobileController@getQuantity');
    $router->get('/{id}', 'MobileController@find');
    $router->get('/', 'MobileController@getAll');
});

$router->group(['prefix' => 'accesory'], function() use($router){
    $router->post('/', 'AccesoryController@store');
    $router->post('/{id}', 'AccesoryController@edit');
    $router->delete('/{id}', 'AccesoryController@destroy');
    $router->get('/{id}', 'AccesoryController@find');
    $router->get('/', 'AccesoryController@getAll');
});

$router->group(['prefix' => 'logIn'], function() use($router){
    $router->post('/', 'AuthController@authenticate');
});
