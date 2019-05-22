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

/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', 'IndexController@index');
Route::post('/login', 'IndexController@login');
Route::get('/logout', 'IndexController@logout');
Route::post('/getBetOrders', 'IndexController@getBetOrders');
Route::get('/getActivities', 'IndexController@getActivities');
Route::post('/activityApplying', 'IndexController@activityApplying');


/*
Route::get('/kyo/test', 'KyoController@test');
Route::post('/kyo/test', 'KyoController@test');
*/
