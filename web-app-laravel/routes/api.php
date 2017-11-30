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

Route::post('/action/command', 'ActionController@command')->name('action.command');

Route::post('/action/command-from-xacml', 'ActionController@commandFromXacml');


// api group route to add auth middleware
Route::middleware('auth:api')->group(function(){

  // device api routes
  Route::get('/devices', 'API\DeviceController@index');
  Route::post('/devices', 'API\Devicecontroller@store');
  Route::get('/devices/{id}', 'API\Devicecontroller@show');
  Route::put('/devices/{id}', 'API\Devicecontroller@update');
  Route::delete('/devices/{id}', 'API\Devicecontroller@destroy');
  Route::get('/devices/{id}/up', 'API\DeviceController@uplink');
  Route::post('/devices/{id}/down', 'API\DeviceController@downlink');

});
