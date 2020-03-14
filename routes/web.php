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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'HomeController@viewHome');
Route::get('/home', 'HomeController@viewHome');
Route::get('/path', 'HomeController@viewPath');
Route::post('/metadados', 'HomeController@viewMetadados');
Route::post('/api', 'HomeController@viewApi');
Route::get('/carregando', 'HomeController@viewLoading');
