<?php

namespace App\Http\Resources;

use App\Entities\Key;
use App\Entities\ProductType;
use App\Models\Favourite;
use App\Models\NegotiationPeriod;
use App\Models\Offer;
use App\Models\Setting;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailsResource extends JsonResource
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

        $chat = $this->chat();

        $appPercentage = Setting::where(['key' => Key::APP_PERCENTAGE])->first();

        $offer = Offer::where(['product_id' => $this->id])
            ->select('price', 'status', 'id')
            ->orderBy('price', 'DESC')->first();
        $max_price = $offer && $this->type === ProductType::BID ? $offer->price : $this->price;

        $period = NegotiationPeriod::where(['period' => $this->period, 'type' => $this->period_type])->first();

        $productData = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $max_price,
            'price_text' => $max_price . ' ' . trans('api.ryal'),
//            'category_id' => $this->category_id,
//            'category_name' => $this->category->name,
//            'sub_category_id' => $this->sub_category_id,
//            'sub_category_name' => @$this->sub_category->name,
//            'sub_sub_category_id' => $this->sub_sub_category_id,
//            'sub_sub_category_name' => @$this->sub_sub_category->name,
            'app_percentage' => $appPercentage->value,
            'chat_id' => $chat ? $chat->id : null,
            'max_price' => $this->max_price,
            'show_user' => $this->show_user,
            'negotiation' => $this->negotiation,
            'percent' => $this->percent,
            'period' => $this->period,
            'period_type' => $this->period_type,
            'period_id' => @$period->id,
            'type' => $this->type,
            'type_text' => trans('api.' . (($this->type === ProductType::DIRECT && $this->negotiation === 1) ? 'negotiation_type' : $this->type)),
            'product_images' => ProductImageResource::collection($this->images),
            'is_favourite' => $favourite ? 1 : 0,
            'remaining_time' => null,
            'user' => [
                'name' => null,
                'image' => null,
                'phone' => null,
            ],
            'comments' => CommentResource::collection($this->comments),
            'fields' => $this->all_fields,
            'can_edit' => $offer ? 0 : $user && $user->id === $this->user_id ? 1 : 0,
        ];
        if ($this->type === ProductType::BID) {
            $endDate = date_create(date('Y-m-d h:i:s a', strtotime("+" . $this->period . " " . $this->period_type,
                strtotime($this->created_at))));
            $createDate = date_create(date('Y-m-d h:i:s a'));
            if ($endDate > $createDate) {
                $remainingTime = date_diff($createDate, $endDate);
                $remainingTime->d += ($remainingTime->m * 30);
                $productData['remaining_time'] =
                    (($remainingTime->d < 10) ? '0' . $remainingTime->d : $remainingTime->d)
                    . ':'
                    . (($remainingTime->h < 10) ? '0' . $remainingTime->h : $remainingTime->h)
                    . ':'
                    . (($remainingTime->i < 10) ? '0' . $remainingTime->i : $remainingTime->i)
                    . ':'
                    . (($remainingTime->s < 10) ? '0' . $remainingTime->s : $remainingTime->s);
            } else {
                $productData['remaining_time'] = '00:00:00:00';
            }
        }
        if ($this->negotiation === 1 && $this->type === ProductType::DIRECT) {
            $productData['type_text'] = trans('api.negotiation_type');
        }
        if ($this->show_user === 1 || $this->type === ProductType::BID) {
            $productOwner = $this->user;
            $productData['user'] = [
                'name' => @$productOwner->name,
                'image' => @$productOwner->image ? url($productOwner->image, [], true) : null,
                'phone' => @$productOwner->full_phone
            ];
        }
        return $productData;
    }
}
