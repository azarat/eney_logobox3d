<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::redirect('/', '/product', '302');

Route::get('/product', 'ProductController@index')->name('product');
Route::get('/product/edit/{id}', 'ProductController@edit');
Route::post('/product/save', 'ProductController@save');

/**
 * Domain
 */
Route::get('/domain', 'DomainController@index')->name('domain');
Route::get('/domain/add', 'DomainController@create');
Route::post('/domain/store', 'DomainController@store');
Route::get('/domain/edit/{id}', 'DomainController@edit');
Route::put('/domain/udpate/{id}', 'DomainController@update');
Route::delete('/domain/delete/{id}', 'DomainController@delete');

/**
 * Type
 */
Route::get('/type', 'TypeController@index')->name('type');
Route::get('/type/edit/{id}', 'TypeController@edit');
Route::put('/type/udpate/{id}', 'TypeController@update');

/**
 * Area
 */
Route::get('/area', 'AreaController@index')->name('area');
Route::get('/area/edit/{id}', 'AreaController@edit');
Route::put('/area/udpate/{id}', 'AreaController@update');

/**
 * Application type
 */
Route::get('/application-type', 'ApplicationTypeController@index')->name('application-type');
Route::get('/application-type/edit/{id}', 'ApplicationTypeController@edit');
Route::put('/application-type/udpate/{id}', 'ApplicationTypeController@update');

/**
 * Iframe
 */
Route::get(
    '/{lang}/iframe/{siteId}/{sessionId}/{productModel}',
    'IframeController@show'
);
