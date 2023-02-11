<?php

namespace Botble\Livestream\Providers;

use Botble\Livestream\Models\Livestream;
use Illuminate\Support\ServiceProvider;
use Botble\Livestream\Repositories\Caches\LivestreamCacheDecorator;
use Botble\Livestream\Repositories\Eloquent\LivestreamRepository;
use Botble\Livestream\Repositories\Interfaces\LivestreamInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class LivestreamServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(LivestreamInterface::class, function () {
            return new LivestreamCacheDecorator(new LivestreamRepository(new Livestream));
        });

        $this->setNamespace('plugins/livestream')->loadHelpers();
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
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Livestream::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Livestream::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-livestream',
                'priority'    => 6,
                'parent_id'   => null,
                'name'        => 'plugins/livestream::livestream.name',
                'icon'        => 'fas fa-stream',
                'url'         => route('livestream.index'),
                'permissions' => ['livestream.index'],
            ]);
        });
    }
}
