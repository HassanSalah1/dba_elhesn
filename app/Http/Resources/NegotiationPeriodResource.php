<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NegotiationPeriodResource extends JsonResource
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
            'name' => $this->period . ' ' . trans('api.' . $this->type),
            'period' => $this->period,
            'type' => $this->type
        ];
    }
}
