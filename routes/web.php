<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');

Route::get('/getcar/{id}', 'HomeController@getCar');
Route::get('/carts', 'HomeController@getTempCarts');

Route::get('/next', 'HomeController@nextStep');

Route::get('/all-carts', 'HomeController@getAllCarts');
Route::post('/carts', 'HomeController@createCarts');
Route::get('/finished', 'HomeController@finished');
