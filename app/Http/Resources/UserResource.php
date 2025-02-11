<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'edit_email' => $this->edited_email,
            'is_verified' => ($this->edited_email !== null) ? false : true,
            'image' => ($this->image !== null) ? url($this->image, [], true) : null,
        ];
    }
}
