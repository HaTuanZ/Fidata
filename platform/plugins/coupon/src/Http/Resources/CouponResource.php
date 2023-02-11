<?php

namespace Botble\Coupon\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'code'                 => $this->name,
			'discount_type'        => $this->discount_type,
			'coupon_amount'        => $this->coupon_amount,
			'expiry_date'          => $this->expiry_date,
			'usage_limit_per_user' => $this->usage_limit_per_user,
		];
	}
}
