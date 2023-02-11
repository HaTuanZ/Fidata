<?php

namespace Botble\Package\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class PackageResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id'    => $this->id,
			'name'  => $this->name,
			'price'  => $this->price,
			'description' => $this->description,
			'content' => $this->content,
			'slug' => $this->slug,
			'access_length' => $this->access_length,
			'access_length_amount' => $this->access_length_amount,
			'access_length_period' => $this->access_length_period,
			'access_start_date' => $this->access_start_date,
			'access_end_date' => $this->access_end_date,
			'image' => $this->image ? RvMedia::url($this->image) : null,
		];
	}
}
