<?php

namespace Botble\Financial\Providers;

use Botble\Financial\Models\Financial;
use Illuminate\Support\ServiceProvider;
use Botble\Financial\Repositories\Caches\FinancialCacheDecorator;
use Botble\Financial\Repositories\Eloquent\FinancialRepository;
use Botble\Financial\Repositories\Interfaces\FinancialInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

use Botble\Financial\Models\Deposit;
use Botble\Financial\Repositories\Caches\DepositCacheDecorator;
use Botble\Financial\Repositories\Eloquent\DepositRepository;
use Botble\Financial\Repositories\Interfaces\DepositInterface;

use Botble\Analysis\Models\Balance;
use Botble\Financial\Repositories\Caches\BalanceCacheDecorator;
use Botble\Financial\Repositories\Eloquent\BalanceRepository;
use Botble\Financial\Repositories\Interfaces\BalanceInterface;

class FinancialServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(FinancialInterface::class, function () {
            return new FinancialCacheDecorator(new FinancialRepository(new Financial));
        });

        $this->app->bind(DepositInterface::class, function () {
            return new DepositCacheDecorator(new DepositRepository(new Deposit));
        });

        $this->app->bind(BalanceInterface::class, function () {
            return new BalanceCacheDecorator(new BalanceRepository(new Balance));
        });

        $this->setNamespace('plugins/financial')->loadHelpers();
    }

    public function boot()
    {
        $this
            ->loadAndPublishConfigurations(['permissions'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes(['web']);

        if (defined('LANGUAGE_MODULE_SCREEN_NAME')) {
            if (defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
                // Use language v2
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Financial::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Financial::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-financial',
                'priority'    => 15,
                'parent_id'   => null,
                'name'        => 'plugins/financial::financial.name',
                'icon'        => 'fa fa-chart-bar',
                'url'         => route('financial.index'),
                'permissions' => ['financial.index'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-financial-deposit',
                'priority'    => 1,
                'parent_id'   => 'cms-plugins-financial',
                'name'        => 'plugins/financial::financial.deposit',
                'icon'        => null,
                'url'         => route('deposit.index'),
                'permissions' => ['financial.index'],
            ])
            ->registerItem([
                'id'          => 'cms-plugins-financial-balance',
                'priority'    => 2,
                'parent_id'   => 'cms-plugins-financial',
                'name'        => 'plugins/financial::financial.balance',
                'icon'        => null,
                'url'         => route('balance.index'),
                'permissions' => ['financial.index'],
            ]);
        });
    }
}
