<?php

namespace Botble\Coin\Providers;

use Botble\Coin\Models\Coin;
use Illuminate\Support\ServiceProvider;
use Botble\Coin\Repositories\Caches\CoinCacheDecorator;
use Botble\Coin\Repositories\Eloquent\CoinRepository;
use Botble\Coin\Repositories\Interfaces\CoinInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;
use Botble\Coin\Commands\CoingeckoCoinsCommand;
use Botble\Coin\Commands\CoingeckoCoinCommand;
use Illuminate\Console\Scheduling\Schedule;

class CoinServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(CoinInterface::class, function () {
            return new CoinCacheDecorator(new CoinRepository(new Coin));
        });

        $this->setNamespace('plugins/coin')->loadHelpers();
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
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Coin::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Coin::class]);
                });
            }
        }

	    $this->app->register(CommandServiceProvider::class);

	    $this->app->booted(function () {
		    $this->app->make(Schedule::class)->command(CoingeckoCoinsCommand::class)->cron('59 23 * * *');
		    //$this->app->make(Schedule::class)->command(CoingeckoCoinsCommand::class)->everyMinute();
	    });

	    $this->app->booted(function () {
		    $this->app->make(Schedule::class)->command(CoingeckoCoinCommand::class)->everyFifteenMinutes();
	    });

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-coin',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/coin::coin.name',
                'icon'        => 'fa fa-coins',
                'url'         => route('coin.index'),
                'permissions' => ['coin.index'],
            ]);
        });
    }
}
