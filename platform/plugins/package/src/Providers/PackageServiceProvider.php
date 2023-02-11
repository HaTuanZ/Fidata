<?php

namespace Botble\Package\Providers;

use Botble\Package\Models\Package;
use Illuminate\Support\ServiceProvider;
use Botble\Package\Repositories\Caches\PackageCacheDecorator;
use Botble\Package\Repositories\Eloquent\PackageRepository;
use Botble\Package\Repositories\Interfaces\PackageInterface;
use Illuminate\Support\Facades\Event;
use Botble\Base\Traits\LoadAndPublishDataTrait;
use Illuminate\Routing\Events\RouteMatched;

class PackageServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function register()
    {
        $this->app->bind(PackageInterface::class, function () {
            return new PackageCacheDecorator(new PackageRepository(new Package));
        });

        $this->setNamespace('plugins/package')->loadHelpers();
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
                \Botble\LanguageAdvanced\Supports\LanguageAdvancedManager::registerModule(Package::class, [
                    'name',
                ]);
            } else {
                // Use language v1
                $this->app->booted(function () {
                    \Language::registerModule([Package::class]);
                });
            }
        }

        Event::listen(RouteMatched::class, function () {
            dashboard_menu()->registerItem([
                'id'          => 'cms-plugins-package',
                'priority'    => 9,
                'parent_id'   => null,
                'name'        => 'plugins/package::package.name',
                'icon'        => 'fas fa-cubes',
                'url'         => route('package.index'),
                'permissions' => ['package.index'],
            ]);
        });
    }
}
