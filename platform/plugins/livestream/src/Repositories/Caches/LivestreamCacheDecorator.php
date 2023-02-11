<?php

namespace Botble\Livestream\Repositories\Caches;

use Botble\Support\Repositories\Caches\CacheAbstractDecorator;
use Botble\Livestream\Repositories\Interfaces\LivestreamInterface;

class LivestreamCacheDecorator extends CacheAbstractDecorator implements LivestreamInterface
{
	public function getNewest()
	{
		return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
	}

	public function getNewestByAuthorId($authorId)
	{
		return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
	}

	public function getByEvents($events)
	{
		return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
	}
}
