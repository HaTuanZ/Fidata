<?php

namespace Botble\Coupon\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Botble\Coupon\Http\Resources\CouponResource;
use Botble\Coupon\Repositories\Interfaces\CouponInterface;
use Illuminate\Http\Request;

class CouponController extends Controller
{
	protected $couponRepository;

	public function __construct(CouponInterface $couponRepository)
	{
		$this->couponRepository = $couponRepository;
	}

	public function findByCode(string $code, BaseHttpResponse $response)
	{

		$coupon = $this->couponRepository->getFirstBy([
			'name'   => $code,
			'status' => BaseStatusEnum::PUBLISHED,
		]);

		if (!$coupon) {
			return $response->setError()->setCode(404)->setMessage('Not found');
		}

		return $response
			->setData(new CouponResource($coupon))
			->toApiResponse();
	}
}
