<?php

namespace Botble\Blog\Providers;

use Botble\Blog\Commands\CoinstatsNewsCommand;
use Botble\Blog\Commands\CoinstatsNewsLatestCommand;
use Botble\Blog\Commands\CoinstatsNewsTrendingCommand;
use Botble\Blog\Commands\CoinstatsNewsBullishCommand;
use Botble\Blog\Commands\CoinstatsNewsBearishCommand;
use Botble\Blog\Commands\CoinstatsNewsHandpickedCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->commands([
			CoinstatsNewsCommand::class,
			CoinstatsNewsLatestCommand::class,
			CoinstatsNewsTrendingCommand::class,
			CoinstatsNewsBullishCommand::class,
			CoinstatsNewsBearishCommand::class,
			CoinstatsNewsHandpickedCommand::class
		]);
	}
}