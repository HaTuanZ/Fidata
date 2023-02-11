<?php

Route::group(['namespace' => 'Botble\Financial\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix() . '/financial', 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'financials', 'as' => 'financial.'], function () {
            Route::resource('', 'FinancialController')->parameters(['' => 'financial']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'FinancialController@deletes',
                'permission' => 'financial.destroy',
            ]);
        });

        Route::group(['prefix' => 'deposit', 'as' => 'deposit.'], function () {
            Route::resource('', 'DepositController')
                ->parameters(['' => 'deposit']);

            Route::get('cancel/{id?}', [
                'as'         => 'cancel',
                'uses'       => 'DepositController@cancel',
                'permission' => 'financial.index',
            ]);
            Route::get('confirm/{id?}', [
                'as'         => 'confirm',
                'uses'       => 'DepositController@confirm',
                'permission' => 'financial.index',
            ]);
        });

        Route::group(['prefix' => 'balance', 'as' => 'balance.'], function () {
            Route::resource('', 'BalanceController')
                ->parameters(['' => 'balance']);
        });

    });

});
