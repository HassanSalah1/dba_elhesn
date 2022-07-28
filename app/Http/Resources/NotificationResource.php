<?php

namespace App\Http\Resources;

use App\Entities\Key;
use App\Entities\OrderType;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $title = null;
        $message = null;
        $user = $this->user;
        if ($this->title_ar !== null) {
            $title = $this->title;
            $message = $this->message;
        } else {
            $title = trans('api.' . $this->title_key);
            $user_name = '';
            $product_name = '';
            $reason = '';
            if ($this->offer) {
                $user_name = $this->offer->user->name;
                $product_name = $this->offer->product->name;
            } else if ($this->order) {
                $user_name = $this->order->user_id === $this->user_id
                    ? $this->order->other_user->name : $this->order->user->name;
                $product_name = $this->order->type === OrderType::DAMAIN
                    ? $this->order->damainOrder->name : $this->order->normalOrder->product->name;
                $reason = $this->order->reason;
            }
            $message = str_replace([
                    '{reason}',
                    '{product_name}',
                    '{days}',
                    '{user_name}',
                ]
                , [
                    $reason,
                    $product_name,
                    Setting::where(['key' => Key::MAX_TIME_TO_PAY])->first()->value,
                    $user_name
                ]
                , trans('api.' . $this->message_key));
        }

        return [
            'id' => $this->id,
            'title' => $title,
            'message' => $message,
            'time' => date('h:i a', strtotime($this->created_at)),
            'date' => date('Y/m/d', strtotime($this->created_at)),
            'type' => $this->type
        ];
    }
}
