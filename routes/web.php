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

use Ixudra\Curl\Facades\Curl;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function() {
    $response = Curl::to('http://www.google.com.tw')->get();
    Log::emergency('ttteesst');
    return $response;
});
