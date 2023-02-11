<?php

namespace Botble\Livestream\Repositories\Interfaces;

use Botble\Support\Repositories\Interfaces\RepositoryInterface;

interface LivestreamInterface extends RepositoryInterface
{
	public function getNewestByAuthorId($authorId);

	public function getNewest();

	public function getByEvents($events);
}