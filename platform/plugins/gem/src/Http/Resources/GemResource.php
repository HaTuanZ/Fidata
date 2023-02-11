<?php

namespace Botble\Gem\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GemResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id'    => $this->id,
			'name'  => $this->name,
			'paid'  => $this->paid,
			'bonus' => $this->bonus,
		];
	}
}
