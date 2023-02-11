<?php

Route::group(['namespace' => 'Botble\MarcoEvent\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'marco-events', 'as' => 'marco-event.'], function () {
            Route::resource('', 'MarcoEventController')->parameters(['' => 'marco-event']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'MarcoEventController@deletes',
                'permission' => 'marco-event.destroy',
            ]);
        });
    });

});
