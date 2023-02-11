<?php

namespace Botble\Analysis\Providers;

use Botble\Base\Traits\LoadAndPublishDataTrait;
use Botble\Analysis\Facades\AnalysisFacade;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class AnalysisServiceProvider extends ServiceProvider
{
	use LoadAndPublishDataTrait;

	/**
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
	 */
	public function boot()
	{
		if (is_plugin_active('blog')) {
			$this->setNamespace('plugins/analysis')
			     ->loadAndPublishConfigurations(['general'])
			     ->loadAndPublishTranslations()
			     ->loadAndPublishViews()
				 ->loadRoutes(['web','api']);

			AliasLoader::getInstance()->alias('Analysis', AnalysisFacade::class);

			$this->app->booted(function () {
				$this->app->register(HookServiceProvider::class);
			});
		}
	}
}
