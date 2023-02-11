<?php

Route::group(['namespace' => 'Botble\Gem\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'gems', 'as' => 'gem.'], function () {
            Route::resource('', 'GemController')->parameters(['' => 'gem']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'GemController@deletes',
                'permission' => 'gem.destroy',
            ]);
        });
    });

});
