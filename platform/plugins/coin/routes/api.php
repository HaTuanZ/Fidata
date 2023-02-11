<?php
Route::group([
    'middleware' => 'api',
    'prefix'     => 'api',
    'namespace'  => 'Botble\Coin\Http\Controllers\API',
], function () {
    Route::get('v2/coins/all', 'CoinController@getAll');
    Route::get('v3/coins/{id?}', 'CoinController@getCoins');
    Route::post('v3/coin/single', [
        'as' => 'api.coin.tracking',
        'uses' => 'CoinController@getCoin'
    ]);
    Route::post('v3/coin/monthly', [
        'as' => 'api.coin.monthly',
        'uses' => 'CoinController@getMonthlyCoin'
    ]);
    Route::get('v3/coin/prices', [
        'as' => 'api.coin.prices',
        'uses' => 'CoinController@getCoinPrices'
    ]);
    Route::get('v3/coin/google_access_token', [
        'as' => 'api.coin.access_token_google',
        'uses' => 'CoinController@getAccessTokenGoogle'
    ]);
    Route::get('v3/coin/google_sheet', [
        'as' => 'api.coin.google_sheet',
        'uses' => 'CoinController@getGoogleSheet'
    ]);
    Route::get('v3/coin/google_sheet', [
        'as' => 'api.coin.google_sheet',
        'uses' => 'CoinController@getGoogleSheet'
    ]);
    Route::get('v3/coin_id/{coin_id?}', [
        'as' => 'api.coin.coin_id',
        'uses' => 'CoinController@getByCoinId'
    ]);
    Route::get('v3/events', [
        'as' => 'api.events',
        'uses' => 'CoinController@getEvents'
    ]);
    Route::post('v3/trending', [
        'as' => 'api.trending',
        'uses' => 'CoinController@getCoingeckoTrending'
    ]);
    Route::post('v3/cryptorank', [
        'as' => 'api.cryptorank',
        'uses' => 'CoinController@getCryptorank'
    ]);
    Route::post('v3/coincap', [
        'as' => 'api.coincap',
        'uses' => 'CoinController@getCoincap'
    ]);
    Route::post('v3/coin-pair', [
        'as' => 'api.coinpair',
        'uses' => 'CoinController@getCoinpair'
    ]);
    Route::post('v3/coinglass', [
        'as' => 'api.coinglass',
        'uses' => 'CoinController@getCoinglass'
    ]);
    Route::post('v3/messari-metrics', [
        'as' => 'api.messari_metrics',
        'uses' => 'CoinController@getMessariMetrics'
    ]);
    Route::get('migration/user', [
        'as' => 'api.migration_user',
        'uses' => 'CoinController@getMigrationUser'
    ]);
    Route::post('llama-raises', [
        'as' => 'api.llama_raises',
        'uses' => 'CoinController@getLlamaRaises'
    ]);
    Route::post('quantifycrypto-coin', [
        'as' => 'api.quantifycrypto_coin',
        'uses' => 'CoinController@getQuantifycryptoCoin'
    ]);
});
