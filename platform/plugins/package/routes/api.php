<?php
Route::group([
	'middleware' => 'api',
	'prefix'     => 'api',
	'namespace'  => 'Botble\Package\Http\Controllers\API',
], function () {
	Route::get('packages', 'PackageController@index');
	Route::get('package/{id}', 'PackageController@findById');
});