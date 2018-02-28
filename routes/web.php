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
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function() {
    $response = Curl::to('http://www.google.com.tw')->get();
    $user = ['name' => 'John', 'age' => 65];
    Log::channel('slack')->info('User: ' . print_r($user, true));
    return $response;
});

Route::post('/webhook', function(Request $request){
    Log::channel('slack')->info($request->all());
});
