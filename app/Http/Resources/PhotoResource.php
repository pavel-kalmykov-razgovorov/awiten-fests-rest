<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class PhotoResource extends Resource
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
            'festival' => [
                'id' => $this->festival->id,
                'name' => $this->festival->name,
                'date' => $this->festival->date,
                'province' => $this->festival->province,
                'location' => $this->festival->location,
            ]
        ];
    }
}
