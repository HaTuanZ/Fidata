<?php
Route::group([
	'middleware' => 'api',
	'prefix'     => 'api/v1',
	'namespace'  => 'Botble\Gem\Http\Controllers\API',
], function () {
	Route::get('gems', 'GemController@index');
	Route::get('gem/{id}', 'GemController@findById');
	Route::get('analysis', 'GemController@getAnalysis');
	Route::get('analysis/{id}', 'GemController@getAnalysisByParent');
});