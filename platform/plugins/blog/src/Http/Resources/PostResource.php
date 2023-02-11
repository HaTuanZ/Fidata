<?php

namespace Botble\Blog\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class PostResource extends JsonResource
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
            'id'                  => $this->id,
            'name'                => $this->name,
            'slug'                => $this->slug,
            'description'         => $this->description,
            'content'             => $this->content,
            'image'               => $this->image ? RvMedia::url($this->image) : null,
	        'cloudflare_image_id' => $this->cloudflare_image_id,
            'categories'          => CategoryResource::collection($this->categories),
            'tags'                => TagResource::collection($this->tags),
	        'published_at'        => $this->published_at,
            'created_at'          => $this->created_at,
            'updated_at'          => $this->updated_at,
            'author_id'           => $this->author->id,
            'author_name'         => $this->author->name,
	        'ref_id'              => $this->ref_id,
            'custom_slug'         => $this->custom_slug,
            'source'              => $this->source,
            'source_link'         => $this->source_link,
            'link'                => $this->link,
	        'reactions_count'     => $this->reactions_count,
            'coins'               => $this->coins
        ];
    }
}
