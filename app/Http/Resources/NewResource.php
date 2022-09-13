<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $image = $this->image();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_description' => $this->short_description,
            'image' => $image ? url($image->image) : null,
            'category' => $this->category->name
        ];
    }
}
