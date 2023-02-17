<?php

Route::group(['namespace' => 'Botble\ApiKeys\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'api-keys', 'as' => 'api-keys.'], function () {
            Route::resource('', 'ApiKeysController')->parameters(['' => 'api-keys']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'ApiKeysController@deletes',
                'permission' => 'api-keys.destroy',
            ]);
        });
    });
});
