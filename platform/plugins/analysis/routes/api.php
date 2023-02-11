<?php
Route::group([
    'middleware' => 'api',
    'prefix'     => 'api',
    'namespace'  => 'Botble\Analysis\Http\Controllers\API',
], function () {
    Route::post('currency-symbols', [
        'as' => 'api.currency_symbols',
        'uses' => 'MemberController@getCurrencySymbols'
    ]);
    Route::post('wallets', [
        'as' => 'api.wallets',
        'uses' => 'MemberController@getWallets'
    ]);
});
