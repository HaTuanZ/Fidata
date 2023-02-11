<?php

namespace Botble\Analysis\Facades;

use Botble\Analysis\Supports\Analysis;
use Illuminate\Support\Facades\Facade;

class AnalysisFacade extends Facade
{
	/**
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return Analysis::class;
	}
}
