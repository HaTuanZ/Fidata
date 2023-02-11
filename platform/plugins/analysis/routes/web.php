<?php

Route::group(['namespace' => 'Botble\Analysis\Http\Controllers', 'middleware' => ['web', 'core']], function () {

	if (defined('THEME_MODULE_SCREEN_NAME')) {
		Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
			Route::get('analysis/{state_id?}', [
				'as'   => 'public.analysis',
				'uses' => 'PublicController@index',
			]);
            Route::get('analysis/project/{slug?}', [
                'as'   => 'public.analysis.project',
                'uses' => 'PublicController@index',
            ]);
            Route::get('analysis/market-overview/{slug?}', [
                'as'   => 'public.analysis.market-overview',
                'uses' => 'PublicController@index',
            ]);
            Route::get('project/{coin_id?}', [
                'as'   => 'public.project',
                'uses' => 'PublicController@project',
            ]);

            Route::group([
                'middleware' => ['member'],
            ], function () {
                Route::get('account/my-gems', [
                    'as'   => 'public.account.my_gems',
                    'uses' => 'PublicController@accountMyGems',
                ]);
                Route::get('account/get-current-user', [
                    'as'   => 'public.account.current_user',
                    'uses' => 'PublicController@getCurrentUser',
                ]);
                Route::post('account/set-gem-collect', [
                    'as'   => 'public.account.gem_collect',
                    'uses' => 'PublicController@setGemCollect',
                ]);
                Route::get('account/history', [
                    'as' => 'public.account.history',
                    'uses' => 'PublicController@getGemsHistory'
                ]);
                Route::get('account/activity/{slug?}', [
                    'as'   => 'public.account.activity',
                    'uses' => 'PublicController@accountActivity',
                ]);
                Route::get('account/get-current-user-referral', [
                    'as'   => 'public.account.current_user_referral',
                    'uses' => 'PublicController@getCurrentUserReferral',
                ]);
                Route::get('account/users', [
                    'as' => 'public.account.users',
                    'uses' => 'PublicController@getUsers'
                ]);
                Route::post('account/commission-history', [
                    'as' => 'public.account.commission_history',
                    'uses' => 'PublicController@getCommissionHistory'
                ]);
                Route::get('account/gem-rewards', [
                    'as'   => 'public.account.gem_rewards',
                    'uses' => 'PublicController@getGemRewards',
                ]);
                Route::post('account/apply-coupon', [
                    'as' => 'public.account.apply_coupon',
                    'uses' => 'PublicController@setApplyCoupon'
                ]);
                Route::post('account/submit-package', [
                    'as' => 'public.account.submit_package',
                    'uses' => 'PublicController@setSubmitPackage'
                ]);
                Route::get('account/deposit', [
                    'as'   => 'public.account.deposit',
                    'uses' => 'PublicController@getDeposit',
                ]);
                Route::post('account/submit-deposit', [
                    'as' => 'public.account.submit_deposit',
                    'uses' => 'PublicController@setSubmitDeposit'
                ]);
                Route::get('account/balance', [
                    'as'   => 'public.account.balance',
                    'uses' => 'PublicController@getBalance',
                ]);
                Route::get('account/balances', [
                    'as' => 'public.account.balances',
                    'uses' => 'PublicController@getBalances'
                ]);
                Route::post('account/balance-logs', [
                    'as' => 'public.account.balance_logs',
                    'uses' => 'PublicController@getBalanceLogs'
                ]);
                Route::post('account/deposits', [
                    'as' => 'public.account.deposits',
                    'uses' => 'PublicController@getDeposits'
                ]);
            });
		});

	}
});
