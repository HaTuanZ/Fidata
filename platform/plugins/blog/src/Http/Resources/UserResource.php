<?php

namespace Botble\Blog\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class UserResource extends JsonResource
{
	/**
	 * Transform the resource into an array.
	 *
	 * @param Request $request
	 * @return array
	 */
	public function toArray($request)
	{
		return [
			'id'              => $this->id,
			'first_name'      => $this->first_name,
			'last_name'       => $this->last_name,
			'name'            => $this->first_name." ".$this->last_name,
		];
	}
}
