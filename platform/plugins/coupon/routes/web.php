<?php

Route::group(['namespace' => 'Botble\Coupon\Http\Controllers', 'middleware' => ['web', 'core']], function () {

    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'coupons', 'as' => 'coupon.'], function () {
            Route::resource('', 'CouponController')->parameters(['' => 'coupon']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CouponController@deletes',
                'permission' => 'coupon.destroy',
            ]);

	        Route::group(['prefix' => 'applied'], function () {
		        Route::get('', [
			        'as'         => 'applied',
			        'uses'       => 'CouponController@getApplied',
			        'permission' => 'coupon.applied',
		        ]);
	        });

        });

    });

});
