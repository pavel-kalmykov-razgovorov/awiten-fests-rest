<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PostResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'lead' => $this->lead,
            'body' => $this->body,
            'festival' => FestivalResource::make($this->whenLoaded('festival'))
        ];
    }
}
