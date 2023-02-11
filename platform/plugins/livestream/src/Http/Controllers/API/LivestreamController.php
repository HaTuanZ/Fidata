<?php

namespace Botble\Livestream\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Livestream\Http\Resources\LivestreamResource;
use Botble\Livestream\Repositories\Interfaces\LivestreamInterface;
use Illuminate\Http\Request;
use Botble\Livestream\Models\Livestream;

class LivestreamController extends Controller
{
	protected $livestreamRepository;

	public function __construct(LivestreamInterface $livestreamRepository)
	{
		$this->livestreamRepository = $livestreamRepository;
	}

	public function findNewestByAuthorId(Request $request, BaseHttpResponse $response)
	{
		$user_id = $request->route("id");
		if($user_id) {
			$events = $this->livestreamRepository->getNewestByAuthorId($user_id);
		} else {
			$events = $this->livestreamRepository->getNewest();
		}

		if (!$events) {
			return $response->setError()->setCode(404)->setMessage('Not found');
		}

		return $response
			->setData(LivestreamResource::collection($events))
			->toApiResponse();
	}

	public function findByEvents(Request $request, BaseHttpResponse $response) {
		$event = $request->input("id");
		$events = explode(",", $event);
		$data = $this->livestreamRepository->getByEvents($events);

		return $response
			->setData(LivestreamResource::collection($data))
			->toApiResponse();
	}

	public function findById(string $id, BaseHttpResponse $response)
	{

		$event = $this->livestreamRepository->getFirstBy([
			'id'     => $id,
			'status' => BaseStatusEnum::PUBLISHED,
		]);

		if (!$event) {
			return $response->setError()->setCode(404)->setMessage('Not found');
		}

		return $response
			->setData(new LivestreamResource($event))
			->toApiResponse();
	}
}
