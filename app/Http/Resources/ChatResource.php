<?php

namespace App\Http\Resources;

use App\Repositories\General\UtilsRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $message = $this->message()->orderBy('id', 'DESC')->first();

        $user = auth()->user();

        $messageUser = $user->id == $this->user_id ? $this->owner : $this->user;

        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'members' => [
                'user1' => [
                    'id' => $this->user_id,
                    'name' => $this->user->name,
                    'image' => $this->user->image_url,
                ],
                'user2' => [
                    'id' => $this->owner_id,
                    'name' => $this->owner->name,
                    'image' => $this->owner->image_url,
                ],
            ],
            'message' => [
                'id' => @$message->id,
                'message' => @$message->message,
                'user' => [
                    'id' => $messageUser->id,
                    'name' => $messageUser->name,
                    'image' => $messageUser->image_url,
                ],
                'datetime' => $message ?
                    UtilsRepository::translateTime(date('A h:i d/m/Y', strtotime($message->created_at))) : ''
            ],
            'unseen_count' => $this->message()->where('seen', 0)->count()
        ];
    }
}
