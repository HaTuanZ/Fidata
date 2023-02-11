<?php

namespace Botble\Analysis\Supports;

class Analysis
{
	/**
	 * @param string | array $model
	 * @return $this
	 */
	public function registerModule($model): self
	{
		if (!is_array($model)) {
			$model = [$model];
		}
		config([
			'plugins.analysis.general.supported' => array_merge(config('plugins.analysise.general.supported', []), $model),
		]);

		return $this;
	}

	/**
	 * @return array
	 */
	public function supportedModules()
	{
		return config('plugins.analysis.general.supported', []);
	}

	/**
	 * @return array
	 */
	public function isSupportedModule(string $model): bool
	{
		return in_array($model, $this->supportedModules());
	}
}
