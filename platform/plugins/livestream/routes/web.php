<?php

Route::group(['namespace' => 'Botble\Livestream\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'livestreams', 'as' => 'livestream.'], function () {
            Route::resource('', 'LivestreamController')->parameters(['' => 'livestream']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'LivestreamController@deletes',
                'permission' => 'livestream.destroy',
            ]);
        });
    });

});
