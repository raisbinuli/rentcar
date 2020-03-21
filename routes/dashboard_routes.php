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

Route::get('/', 'MainController@index');
Route::get('/cars', 'CarsController@index');
Route::get('/cars/{id}', 'CarsController@getCar');
Route::post('/cars', 'CarsController@store');
Route::put('/cars', 'CarsController@update');
Route::delete('/cars/{id}', 'CarsController@destroy');