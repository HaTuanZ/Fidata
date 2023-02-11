<?php

namespace Botble\Gem\Providers;

use Botble\Gem\Models\Gem;
use Illuminate\Support\ServiceProvider;
use Botble\Gem\Repositories\Caches\GemCacheDecorator;
use Botble\Gem\Repositories\Eloquent\GemRepository;
use Botble\Gem\Repositories\Interfaces\GemInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class GemServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(GemInterface::class, function () {
            return new GemCacheDecorator(new GemRepository(new Gem));
        });

        $this->setNamespace('plugins/gem')->loadHelpers();
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
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Gem::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Gem::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-gem',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/gem::gem.name',
                'icon'        => 'fa fa-gem',
                'url'         => route('gem.index'),
                'permissions' => ['gem.index'],
            ]);
        });
    }
}
