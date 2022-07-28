<?php

namespace App\Http\Resources;

use App\Entities\OrderType;
use App\Entities\OrderUserType;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
        $actions = $this->actions();
        $otherUser = [];
        $productName = '';
        $images = [];
        $offers = [];
        if ($this->type === OrderType::DAMAIN && $this->damainOrder) {
            $productName = $this->damainOrder->name;
        } else {
            $normalOrder = $this->normalOrder;
            if ($normalOrder) {
                $productName = $normalOrder->product->name;
            } else {
                $offers = $this->offers;
                if(count($offers) > 0){
                    $productName = $offers[0]->product->name;
                }
            }
        }

        if ($user->id === $this->user_id || $this->user_id == 0) {
            $otherUser = [
                'type' => $this->user_type === OrderUserType::BUYER ?
                    trans('api.seller') : trans('api.buyer'),
                'name' => $this->other_user->name,
                'phone' => $this->other_user->full_phone,
                'image' => $this->other_user->image ?
                    url($this->other_user->image, [], true) : null,
            ];
        } else if ($user->id === $this->other_user_id) {
            $otherUser = [
                'type' => trans('api.' . $this->user_type),
                'name' => @$this->user->name,
                'phone' => @$this->user->full_phone,
                'image' => $this->user && $this->user->image ?
                    url($this->user->image, [], true) : null,
            ];
            if ($otherUser['image'] != null) {
                $images[] = $otherUser['image'];
            }
        }

        if (count($offers) > 0) {
            foreach ($offers as $offer) {
                if (($offer->user_id !== $this->user_id && $this->user_id !== 0)
                    || ($this->user_id === 0)) {
                    //
                    $images[] = $offer->user->image ?
                        url($offer->user->image, [], true) : null;
                }
            }
        }

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'user_type' => $this->user_type,
            'other_user_id' => $this->other_user_id,
            'other_user' => $otherUser,
            'images' => $images,
            'product_name' => $productName,
            'type' => $this->type,
            'type_text' => trans('api.' . $this->type),
            'date' => date('d/m/Y', strtotime($this->created_at)),
            'status' => $this->status,
            'actions' => $actions
        ];
    }
}
