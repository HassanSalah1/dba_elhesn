<?php

namespace App\Http\Resources;

use App\Entities\OrderUserType;
use App\Entities\ProductType;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailsResource extends JsonResource
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
        $normalOrder = $this->normalOrder;
        $actions = $this->actions();


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
                'name' => @$this->user->name,
                'phone' => @$this->user->full_phone,
                'image' => @$this->user->image ? url(@$this->user->image, [], true) : null,
            ];
        }

        $product = @$normalOrder->product;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'other_user_id' => $this->other_user_id,
            'other_user' => $otherUser,
            'product' => [
                'id' => @$normalOrder->product_id,
                'name' => @$product->name,
                'category_id' => @$product->category_id,
                'category_name' => @$product->category->name,
                'sub_category_id' => @$product->sub_category_id,
                'sub_category_name' => @$product->sub_category->name,
                'sub_sub_category_id' => @$product->sub_sub_category_id,
                'sub_sub_category_name' => @$product->sub_sub_category->name,
                'description' => @$product->description,
                'price' => @$product->price,
                'product_images' => $product ? ProductImageResource::collection($product->images) : [],
                'fields' => @$normalOrder->fields != null ? json_decode($normalOrder->fields) : null,
            ],
            'refuse_delivery' => [
                'reason' => @$this->refuse_reason->reason,
                'images' => ProductImageResource::collection($this->refuse_reason ?
                    $this->refuse_reason->images : []),
            ],
            'refuse_reason' => $this->reason,
            'pay' => $normalOrder ? $normalOrder->calc_pricing() : null,
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
