<?php

Route::group(['namespace' => 'Botble\Coin\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'coins', 'as' => 'coin.'], function () {
            Route::resource('', 'CoinController')->parameters(['' => 'coin']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CoinController@deletes',
                'permission' => 'coin.destroy',
            ]);
        });
    });

	Route::get('test', 'CoinController@test')->name('test');

    Route::get('events', [
        'as'   => 'public.events',
        'uses' => 'CoinController@getEvents',
    ]);

    Route::get('coins/{coin_id}/{slug?}', [
        'as'   => 'public.coins.coin',
        'uses' => 'CoinController@getCoins',
    ]);

});
/*
Route::get('/{any?}', function () {
	return view('home');
})->where('any', '^(?!api\/)[\/\w\.\,-]*');

Route::get('/{any?}', function () {
	return view('home');
})->where('any', '^(?!api\/)[\/\w\.\,-]*');

Route::get('/{any?}', function () {
	return view('welcome');
})->where('any', '^(?!admin).+');
*/