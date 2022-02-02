<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('application-type', ApplicationTypeController::class);
    $router->resource('area', AreaController::class);
    $router->resource('type', TypeController::class);
    $router->resource('product', ProductController::class);
    $router->resource('preset', AreaPresetsController::class);
});
