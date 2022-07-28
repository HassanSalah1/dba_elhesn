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
        $country = @$this->city->country;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phonecode' => $this->phonecode,
            'phone' => $this->phone,
            'email' => $this->email,
            'edit_phonecode' => $this->edit_phonecode,
            'edit_phone' => $this->edit_phone,
            'is_verified' => ($this->edit_phone !== null) ? false : true,
            'city_id' => $this->city_id,
            'city_name' => $this->city->name,
            'country_id' => $country ? $country->id : null,
            'country_name' => $country ? $country->name : null,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'image' => ($this->image !== null) ? url($this->image, [] , true) : null,
            'bank_accounts' => UserBankAccountResource::collection($this->bankAccounts),
        ];
    }
}
