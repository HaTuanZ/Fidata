<?php

namespace Botble\Package\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Package\Http\Resources\PackageResource;
use Botble\Package\Repositories\Interfaces\PackageInterface;
use Illuminate\Http\Request;
use Botble\Package\Models\Package;

class PackageController extends Controller
{
	protected $packageRepository;

	public function __construct(PackageInterface $packageRepository)
	{
		$this->packageRepository = $packageRepository;
	}

	public function index(Request $request, BaseHttpResponse $response)
	{
		$data = $this->packageRepository
			->advancedGet([
				//'with'      => ['slugable'],
				'condition' => ['status' => BaseStatusEnum::PUBLISHED],
				'paginate'  => [
					'per_page'      => (int)$request->input('per_page', 10),
					'current_paged' => (int)$request->input('page', 1),
				],
			]);

		return $response
			->setData(PackageResource::collection($data))
			->toApiResponse();
	}

	public function findById(string $id, BaseHttpResponse $response)
	{

		$package = $this->packageRepository->getFirstBy([
			'id'     => $id,
			'status' => BaseStatusEnum::PUBLISHED,
		]);

		if (!$package) {
			return $response->setError()->setCode(404)->setMessage('Not found');
		}

		return $response
			->setData(new PackageResource($package))
			->toApiResponse();
	}
}
