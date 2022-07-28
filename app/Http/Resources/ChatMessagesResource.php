<?php

namespace App\Http\Resources;

use App\Repositories\General\UtilsRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatMessagesResource extends JsonResource
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
            'message' => $this->message,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'image' => $this->user->image ? url($this->user->image) : null,
            ],
            'time' => UtilsRepository::translateTime(date('h:i A' , strtotime($this->created_at)))
        ];
    }
}
