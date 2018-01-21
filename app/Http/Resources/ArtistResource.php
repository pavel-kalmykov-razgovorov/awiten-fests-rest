<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ArtistResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'country' => $this->country,
            'soundcloud' => $this->soundcloud,
            'website' => $this->website,
            'profileUrl' => route('images.artist_profile', ['id' => $this->id]),
            'headerUrl' => route('images.artist_header', ['id' => $this->id]),
            'festivals' => FestivalResource::collection($this->whenLoaded('festivals')),
            'confirmed' => $this->whenPivotLoaded('artist_festival', function () {
                return $this->pivot->confirmed;
            }),
            'genres' => GenreResource::collection($this->whenLoaded('genres'))
        ];
    }
}
