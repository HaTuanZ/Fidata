<?php

namespace Botble\MarcoEvent\Providers;

use Botble\MarcoEvent\Models\MarcoEvent;
use Illuminate\Support\ServiceProvider;
use Botble\MarcoEvent\Repositories\Caches\MarcoEventCacheDecorator;
use Botble\MarcoEvent\Repositories\Eloquent\MarcoEventRepository;
use Botble\MarcoEvent\Repositories\Interfaces\MarcoEventInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class MarcoEventServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(MarcoEventInterface::class, function () {
            return new MarcoEventCacheDecorator(new MarcoEventRepository(new MarcoEvent));
        });

        $this->setNamespace('plugins/marco-event')->loadHelpers();
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
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(MarcoEvent::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([MarcoEvent::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-marco-event',
                'priority'    => 6,
                'parent_id'   => null,
                'name'        => 'plugins/marco-event::marco-event.name',
                'icon'        => 'fa fa-calendar',
                'url'         => route('marco-event.index'),
                'permissions' => ['marco-event.index'],
            ]);
        });
    }
}
