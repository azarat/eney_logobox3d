<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Product
 */
Route::post(
    '{lang}/domain/{domainKey}/product/{productModel}',
    'SessionController@init'
);
Route::post(
    '{lang}/domain/{domainKey}/product/{productModel}/session/{sessionId}',
    'SessionController@continue'
);

Route::any('import/product-relations/manually', 'Api\ProductController@manuallyImport');

/**
 * Iframe
 */
Route::get(
    '{lang}/areas/by-product/{productModel}',
    'Api\AreaController@getByProductModel'
);
Route::get(
    '{lang}/application-types/by-product/{productModel}',
    'Api\ApplicationTypeController@getByProductModel'
);

/**
 * Prints
 */
Route::get(
    '{lang}/{siteId}/{sessionId}/prints/{productModel}',
    'Api\PrintsController@index'
);
Route::get(
    '{siteId}/{sessionId}/prints/during/{productModel}',
    'Api\PrintsController@getDuring'
);
Route::get(
    '{siteId}/{sessionId}/printsdata/during/{productModel}',
    'Api\PrintsDataController@getDuringPrintsData'
);
Route::post(
    '{siteId}/{sessionId}/prints',
    'Api\PrintsController@createOrUpdate'
);
Route::post(
    '{siteId}/{sessionId}/{productModel}/printsdata',
    'Api\PrintsDataController@createOrUpdate'
);
Route::delete(
    '{siteId}/{sessionId}/prints/{id}',
    'Api\PrintsController@destroy'
);
Route::post( // Caled when product asigned to cart
    '{siteId}/{sessionId}/prints/confirm/{productModel}/{cartProductId}',
    'Api\PrintsController@confirm'
);

/**
 * Calc
 */
Route::post( // Request need containe body '{ "products": { <product-cart-id>: <qty>, "123": "4" } }'
    '{lang}/calc/{siteId}/{sessionId}',
    'Api\CalcController@index'
);
Route::post( // Request need containe body '{ "products": { <product-cart-id>: <qty>, "123": "4" } }'
    '{lang}/calc/total/{siteId}/{sessionId}',
    'Api\CalcController@onlyTotal'
);

/**
 * Settings
 */
Route::get(
    '{siteid}/settings/colors',
    'Api\SettingsController@colors'
);

/**
 * File uploads
 */
Route::post(
    'upload-file',
    'Api\FileController@store'
);

/**
 * Data for pdf
 */
Route::get(
    'pdf-data/{print_id}/{lang?}',
    'Api\PrintsDataController@getPdfData'
);
