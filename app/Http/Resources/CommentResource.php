<?php

namespace App\Http\Resources;

use App\Repositories\General\UtilsRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->user;
        return [
            'id' => $this->id,
            'comment' => $this->comment,
            'user' => [
                'name' => $user->name,
                'image' => $user->image !== null ? url($user->image , [] , true) : null,
            ],
            'time' => UtilsRepository::translateTime(date('h:i a' , strtotime($this->created_at))),
            'date' =>  date('Y-m-d' , strtotime($this->created_at)),
        ];
    }
}
