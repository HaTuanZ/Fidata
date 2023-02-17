<?php
Route::group([
    'middleware' => 'api',
    'prefix'     => 'api',
    'namespace'  => 'Botble\ApiKeys\Http\Controllers\API',
], function () {
    Route::get('orders', [
        'as' => 'api.orders',
        'uses' => 'OrderController@index'
    ]);
});
