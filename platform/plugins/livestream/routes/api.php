<?php
Route::group([
	'middleware' => 'api',
	'prefix'     => 'api',
	'namespace'  => 'Botble\Livestream\Http\Controllers\API',
], function () {
	Route::get('livestream/{id?}', 'LivestreamController@findNewestByAuthorId');
	Route::get('livestreame/{id}', 'LivestreamController@findById');
	Route::get('livestreams', 'LivestreamController@findByEvents');
});