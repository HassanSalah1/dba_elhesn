<?php

namespace App\Http\Resources;

use App\Entities\ProductType;
use App\Models\Favourite;
use App\Models\Offer;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = auth()->user();
        $favourite = Favourite::where([
            'product_id' => $this->id,
            'user_id' => $user ? $user->id : null,
        ])->first();
        $image = $this->image();

        $offer = Offer::where(['product_id' => $this->id])
            ->select('price', 'status', 'id')
            ->orderBy('price', 'DESC')->first();
        $max_price = $offer && $this->type === ProductType::BID ? $offer->price : $this->price;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $max_price,
            'price_text' => $max_price . ' ' . trans('api.ryal'),
            'type' => $this->type,
            'type_text' => trans('api.' . (($this->type === ProductType::DIRECT && $this->negotiation === 1) ? 'negotiation_type' : $this->type)),
            'negotiation' => $this->negotiation,
            'product_images' => $image && $image->image !== null ? url($image->image , [] , env('APP_ENV') === 'local' ?  false : true) : null,
            'is_favourite' => $favourite ? 1 : 0,
        ];
    }
}
