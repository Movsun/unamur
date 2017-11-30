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

Route::get('/', 'HomeController@index');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/device', 'DeviceController@index')->name('device');
Route::get('/device/add', 'DeviceController@add')->name('device.add');
Route::post('/device/store', 'DeviceController@store')->name('device.store');
Route::get('/device/edit', 'DeviceController@edit')->name('device.edit');
Route::post('/device/update', 'DeviceController@update')->name('device.update');
Route::get('/device/remove', 'DeviceController@remove')->name('device.remove');
Route::post('/device/delete', 'DeviceController@delete')->name('device.delete');
Route::get('/device/show', 'DeviceController@show')->name('device.show');
Route::get('/device/setting', 'DeviceController@setting')->name('device.setting');
Route::post('/device/updateSetting', 'DeviceController@updateSetting')->name('device.updateSetting');
Route::post('/device/removeSetting', 'DeviceController@removeSetting')->name('device.removeSetting');
Route::get('/action/list', 'ActionController@index')->name('action.list');
Route::get('/action/on-demand', 'ActionController@onDemand')->name('action.on-demand');
Route::post('/action/create-on-demand', 'ActionController@createOnDemand')->name('action.create-on-demand');
Route::get('/action/page/{id}', 'ActionController@page')->name('action.page');

Route::get('setting', 'SettingController@index')->name('setting');

Route::get('/action/automatic', 'ActionController@automatic')->name('action.automatic');
Route::post('/action/create-automatic', 'ActionController@createAutomatic')->name('action.create-automatic');
Route::get('/policy/add', 'PolicyController@add')->name('policy.add');
Route::get('/policy/edit', 'PolicyController@edit')->name('policy.edit');
Route::get('/policy/remove', 'PolicyController@remove')->name('policy.remove');
Route::get('/policy/show', 'PolicyController@show')->name('policy.show');
Route::post('/policy/store', 'PolicyController@store')->name('policy.store');
Route::post('/policy/delete', 'PolicyController@delete')->name('policy.delete');
Route::post('/policy/update', 'PolicyController@update')->name('policy.update');
Route::get('/policy/getXmlFile', 'PolicyController@getXmlFile')->name('policy.getXmlFile');
Route::get('/setting/add', 'SettingController@add')->name('setting.add');
Route::get('/setting/edit', 'SettingController@edit')->name('setting.edit');
Route::get('/setting/remove', 'SettingController@remove')->name('setting.remove');
Route::get('/setting/show', 'SettingController@show')->name('setting.show');
Route::post('/setting/store', 'SettingController@store')->name('setting.store');
Route::post('/setting/update', 'SettingController@update')->name('setting.update');
Route::post('/setting/delete', 'SettingController@delete')->name('setting.delete');

// Route::get('/test', function(Request $request){
// 	dd($reqeust);
// 	return response()->download(resource_path('policy/sample/policy.xml'));
// })->name('test');
Route::get('/action/remove/{id}', 'ActionController@remove')->name('action.remove');

Route::get('/help', 'HelpController@index')->name('help');

Route::get('/setting/change-password', 'SettingController@changePassword')->name('setting.change-password');
Route::get('/setting/change-email', 'SettingController@changeEmail')->name('setting.change-email');
Route::post('/setting/update-password', 'SettingController@updatePassword')->name('setting.update-password');
Route::post('/setting/update-email', 'SettingController@updateEmail')->name('setting.update-email');



Route::get('/gateways', 'GatewayController@index')->name('gateway.index');
Route::get('/gateways/addG', 'GatewayController@addGateway')->name('gateway.addGateway');
Route::post('/gateways/storeG', 'GatewayController@storeGateway')->name('gateway.storeGateway');
Route::get('/gateways/removeG/{id}', 'GatewayController@removeGateway')->name('gateway.removeGateway');
Route::get('/gateways/addN', 'GatewayController@addNode')->name('gateway.addNode');
Route::post('/gateways/storeN', 'GatewayController@storeNode')->name('gateway.storeNode');
Route::get('/gateways/removeN/{id}', 'GatewayController@removeNode')->name('gateway.removeNode');
