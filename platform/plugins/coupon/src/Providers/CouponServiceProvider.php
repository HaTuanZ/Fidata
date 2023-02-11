<?php

namespace Botble\Coupon\Providers;

use Botble\Coupon\Models\Coupon;
use Illuminate\Support\ServiceProvider;
use Botble\Coupon\Repositories\Caches\CouponCacheDecorator;
use Botble\Coupon\Repositories\Eloquent\CouponRepository;
use Botble\Coupon\Repositories\Interfaces\CouponInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class CouponServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(CouponInterface::class, function () {
            return new CouponCacheDecorator(new CouponRepository(new Coupon));
        });

        $this->setNamespace('plugins/coupon')->loadHelpers();
    }

    public function boot()
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web', 'api']);

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
                // Use language v2
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Coupon::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Coupon::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()
	            ->registerItem([
	                'id'          => 'cms-plugins-coupon',
	                'priority'    => 5,
	                'parent_id'   => null,
	                'name'        => 'plugins/coupon::coupon.name',
	                'icon'        => 'fa fa-tags',
	                'url'         => route('coupon.index'),
	                'permissions' => ['coupon.index'],
	            ])
		        ->registerItem([
			        'id'          => 'cms-plugins-coupon-coupon',
			        'priority'    => 1,
			        'parent_id'   => 'cms-plugins-coupon',
			        'name'        => 'plugins/coupon::coupon.name',
			        'icon'        => null,
			        'url'         => route('coupon.index'),
			        'permissions' => ['coupon.index'],
		        ])
	            ->registerItem([
		            'id'          => 'cms-plugins-coupon-coupon',
		            'priority'    => 2,
		            'parent_id'   => 'cms-plugins-coupon',
		            'name'        => 'plugins/coupon::coupon.applied',
		            'icon'        => null,
		            'url'         => route('coupon.applied'),
		            'permissions' => ['coupon.applied'],
	            ]);
        });
    }
}
