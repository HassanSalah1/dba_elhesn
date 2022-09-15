<?php

namespace App\Http\Resources;

use App\Repositories\General\UtilsRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ActionResource extends JsonResource
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
            'image' => $image ? url($image->image) : null,
            'start_date' => UtilsRepository::translateDate(date('d F', strtotime($this->start_date))),
            'created_date' => UtilsRepository::translateDate(date('d F Y', strtotime($this->created_at))),
        ];
    }
}
