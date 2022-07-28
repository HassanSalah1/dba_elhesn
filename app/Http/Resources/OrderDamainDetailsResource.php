<?php

namespace App\Http\Resources;

use App\Entities\OrderUserType;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDamainDetailsResource extends JsonResource
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
        $otherUser = [];
        $damainOrder = $this->damainOrder;
        $actions = $this->damainActions();


        if ($user->id === $this->user_id) {
            $otherUser = [
                'type' => $this->user_type === OrderUserType::BUYER ?
                    trans('api.seller') : trans('api.buyer'),
                'name' => $this->other_user->name,
                'phone' => $this->other_user->full_phone,
                'image' => $this->other_user->image ? url($this->other_user->image, [], true) : null,
            ];
        } else if ($user->id === $this->other_user_id) {
            $otherUser = [
                'type' => trans('api.' . $this->user_type),
                'name' => $this->user->name,
                'phone' => $this->user->full_phone,
                'image' => $this->user->image ? url($this->user->image, [], true) : null,
            ];
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'other_user_id' => $this->other_user_id,
            'other_user' => $otherUser,
            'product' => [
                'id' => 0,
                'name' => $damainOrder->name,
                'category_id' => $damainOrder->category_id,
                'category_name' => @$damainOrder->category->name,
                'sub_category_id' => $damainOrder->sub_category_id,
                'sub_category_name' => @$damainOrder->sub_category->name,
                'sub_sub_category_id' => $damainOrder->sub_sub_category_id,
                'sub_sub_category_name' => @$damainOrder->sub_sub_category->name,
                'description' => $damainOrder->description,
                'price' => $damainOrder->price,
                'latitude' => $damainOrder->latitude,
                'longitude' => $damainOrder->longitude,
                'address' => $damainOrder->address,
                'product_images' => ProductImageResource::collection($this->images),
            ],
            'refuse_delivery' => [
                'reason' => @$this->refuse_reason->reason,
                'images' => ProductImageResource::collection($this->refuse_reason ?
                    $this->refuse_reason->images : []),
            ],
            'refuse_reason' => $this->reason,
            'pay' => $damainOrder->calc_pricing(),
            'type' => $this->type,
            'type_text' => trans('api.' . $this->type),
            'shipment_code' => $this->shipment_code,
            'shipment_type' => $this->shipment_type,
            'date' => date('d/m/Y', strtotime($this->created_at)),
            'status' => $this->status,
            'actions' => $actions
        ];
    }
}
