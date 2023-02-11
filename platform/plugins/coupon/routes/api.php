<?php
Route::group([
	'middleware' => 'api',
	'prefix'     => 'api',
	'namespace'  => 'Botble\Coupon\Http\Controllers\API',
], function () {
	Route::get('coupon/{code}', 'CouponController@findByCode');
});