<?php

namespace App\Http\Resources;

use App\Entities\OrderUserType;
use Cassandra\Type\UserType;
use Illuminate\Http\Resources\Json\JsonResource;

class CreditOrderHistoryResource extends JsonResource
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
        $product = $this->damainOrder ? $this->damainOrder->name : $this->normalOrder->product->name;

        if ($this->user_type === OrderUserType::BUYER && $user->id === $this->user_id){
            $type = trans('api.buy_process');
        }else{
            $type = trans('api.sell_process');
        }

        return [
            'id' => $this->id,
            'product_name' => $product,
            'type' => $type,
            'price' => $this->total_price,
            'date' => $this->created_at->format('Y-m-d')
        ];
    }
}
