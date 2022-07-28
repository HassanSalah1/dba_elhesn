<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'phone' => $this->user->full_phone,
                'image' => $this->user->image ? url($this->user->image, [], true) : null,
            ],
            'price' => $this->price,
            'chat_id' => null,
            'status' => $this->status,
            'actions' => $this->actions()
        ];
    }
}
