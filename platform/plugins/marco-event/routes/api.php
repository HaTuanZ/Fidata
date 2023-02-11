<?php
Route::group([
    'middleware' => 'api',
    'prefix'     => 'api',
    'namespace'  => 'Botble\MarcoEvent\Http\Controllers\API',
], function () {
    Route::get('marco-events', 'MarcoEventController@findByEvents');
});
