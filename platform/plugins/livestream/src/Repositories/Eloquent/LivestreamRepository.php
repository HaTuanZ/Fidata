<?php

namespace Botble\Livestream\Repositories\Eloquent;

use Botble\Support\Repositories\Eloquent\RepositoriesAbstract;
use Botble\Livestream\Repositories\Interfaces\LivestreamInterface;
use Botble\Base\Enums\BaseStatusEnum;
use Carbon\Carbon;

class LivestreamRepository extends RepositoriesAbstract implements LivestreamInterface
{
	public function getNewest()
	{
		$timestamp = time();
		$date = Carbon::parse($timestamp)->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d');
		$time = Carbon::parse($timestamp)->timezone('Asia/Ho_Chi_Minh')->toTimeString();

		$data = $this->model
			->where([
				'status'    => BaseStatusEnum::PUBLISHED
			])
			->whereDate('event_date_time', '>=', $date)
			->whereTime('event_date_time', '>', $time)
			->orderBy('event_datetime', 'desc');

		return $this->applyBeforeExecuteQuery($data)->get();
	}

	public function getNewestByAuthorId($authorId)
	{
		$timestamp = time();
		$date = Carbon::parse($timestamp)->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d');
		$time = Carbon::parse($timestamp)->timezone('Asia/Ho_Chi_Minh')->toTimeString();

		$data = $this->model
			->where([
				'status'    => BaseStatusEnum::PUBLISHED,
				'user_id'   => $authorId,
			])
			->whereDate('event_date_time', '>=', $date)
			->whereTime('event_date_time', '>', $time)
			//->where('event_datetime', '>=', time())
			->orderBy('event_datetime', 'desc');

		//return $this->applyBeforeExecuteQuery($data, true)->first();
		return $this->applyBeforeExecuteQuery($data)->get();
	}

	public function getByEvents($events)
	{
		$timestamp = time();
		$date = Carbon::parse($timestamp)->timezone('Asia/Ho_Chi_Minh')->format('Y-m-d');
		$time = Carbon::parse($timestamp)->timezone('Asia/Ho_Chi_Minh')->toTimeString();
		$datetime = Carbon::now()->timezone('Asia/Ho_Chi_Minh');

		$data = $this->model
			->where('status', BaseStatusEnum::PUBLISHED)
			->whereIn('id', $events)
			//->whereDate('event_date_time', '<=', $date)
			//->whereTime('event_date_time', '<=', $time)
			->where('event_datetime', '<=', strtotime($datetime))
			->orderBy('event_datetime', 'desc');

		return $this->applyBeforeExecuteQuery($data)->get();
	}
}
