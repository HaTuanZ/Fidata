<?php

namespace Botble\Livestream\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class LivestreamResource extends JsonResource
{
	public function toArray($request)
	{
		return [
			'id'             => $this->id,
			'name'           => $this->name,
			'description'    => $this->description,
			'user_id'        => $this->user_id,
			'embled'         => $this->embled,
			'video_url'      => $this->video_url,
			'gem'            => $this->gem,
			'event_date'     => $this->event_date,
			'event_time'     => $this->event_time,
			'event_date_time' => $this->event_date_time,
			'event_datetime' => $this->event_datetime,
			'end_date'       => $this->end_date,
			'end_time'       => $this->end_time,
			'thumbnail'      => $this->thumbnail ? RvMedia::url($this->thumbnail) : null,
		];
	}
}
