<?php

namespace Botble\Coin\Providers;

use Botble\Coin\Commands\CoingeckoCoinsCommand;
use Botble\Coin\Commands\CoingeckoCoinCommand;
use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
	public function boot()
	{
		$this->commands([
			CoingeckoCoinsCommand::class,
			CoingeckoCoinCommand::class,
		]);
	}
}