<?php

namespace Botble\Gem\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Gem\Http\Resources\GemResource;
use Botble\Gem\Repositories\Interfaces\GemInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GemController extends Controller
{
	protected $gemRepository;

	public function __construct(GemInterface $gemRepository)
	{
		$this->gemRepository = $gemRepository;
	}

	public function index(Request $request, BaseHttpResponse $response)
	{
		$data = $this->gemRepository
			->advancedGet([
				//'with'      => ['slugable'],
				'condition' => ['status' => BaseStatusEnum::PUBLISHED],
				'paginate'  => [
					'per_page'      => (int)$request->input('per_page', 10),
					'current_paged' => (int)$request->input('page', 1),
				],
			]);

		return $response
			->setData(GemResource::collection($data))
			->toApiResponse();
	}

	public function findById(string $id, BaseHttpResponse $response)
	{

		$gem = $this->gemRepository->getFirstBy([
			'id'     => $id,
			'status' => BaseStatusEnum::PUBLISHED,
		]);

		if (!$gem) {
			return $response->setError()->setCode(404)->setMessage('Not found');
		}

		return $response
			->setData(new GemResource($gem))
			->toApiResponse();
	}

	public function getAnalysis() {
		$menus = DB::select('select * from menu_nodes where menu_id = 2 AND parent_id = 0 order by position ASC');
		return response()->json($menus);
	}

	public function getAnalysisByParent($parent_id) {
		$menus = DB::select('select * from menu_nodes where parent_id = ?', [$parent_id]);
		return response()->json($menus);
	}
}
