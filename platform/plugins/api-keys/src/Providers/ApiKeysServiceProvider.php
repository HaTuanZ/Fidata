<?php

namespace Botble\ApiKeys\Providers;

use Botble\ApiKeys\Models\ApiKeys;
use Illuminate\Support\ServiceProvider;
use Botble\ApiKeys\Repositories\Caches\ApiKeysCacheDecorator;
use Botble\ApiKeys\Repositories\Eloquent\ApiKeysRepository;
use Botble\ApiKeys\Repositories\Interfaces\ApiKeysInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class ApiKeysServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(ApiKeysInterface::class, function () {
            return new ApiKeysCacheDecorator(new ApiKeysRepository(new ApiKeys));
        });

        $this->setNamespace('plugins/api-keys')->loadHelpers();
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
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(ApiKeys::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([ApiKeys::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-api-keys',
                'priority'    => 5,
                'parent_id'   => null,
                'name'        => 'plugins/api-keys::api-keys.name',
                'icon'        => 'fa fa-list',
                'url'         => route('api-keys.index'),
                'permissions' => ['api-keys.index'],
            ]);
        });
    }
}
