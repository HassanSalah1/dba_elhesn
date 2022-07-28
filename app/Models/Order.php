<?php

namespace App\Models;

use App\Entities\Key;
use App\Entities\OrderStatus;
use App\Entities\OrderType;
use App\Entities\OrderUserType;
use App\Entities\ShipmentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = ['reason', 'type', 'status', 'shipment_id', 'shipment_type', 'shipment_code',
        'user_id', 'user_type', 'other_user_id', 'payment_method_id' , 'shipment_number'
    ];

    protected $appends = ['total_price'];
    protected $casts = [
        'shipment_type' => 'integer'
    ];

    public function damainOrder()
    {
        return $this->hasOne(DamainOrder::class);
    }

    public function normalOrder()
    {
        return $this->hasOne(NormalOrder::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function refuse_reason()
    {
        return $this->hasOne(RefuseReason::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'order_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function other_user()
    {
        return $this->belongsTo(User::class, 'other_user_id');
    }

    public function getPriceAttribute()
    {
        $price = $this->normalOrder ? $this->normalOrder->price :
            ($this->damainOrder ? $this->damainOrder->price : 0);
        return $price;
    }

    public function getTotalPriceAttribute()
    {
        $price = $this->normalOrder ? $this->normalOrder->price :
            ($this->damainOrder ? $this->damainOrder->price : 0);
        $appPercentage = Setting::where(['key' => Key::APP_PERCENTAGE])->first();
        $percentValue = $price * ($appPercentage->value / 100);
        return floatval(number_format($price + $percentValue, 2));
    }

    public function damainActions()
    {
        $user = auth()->user();

        $add_product = false;
        $can_accept = false;
        $can_refuse = false;
        $can_edit = false;
        $can_cancelled = false;
        $can_payment = false;
        $can_shipment = false;
        $can_show_shipment_code = false;
        $can_accept_delivery = false;
        $can_refuse_delivery = false;

        if ($this->status === OrderStatus::WAIT && $this->damainOrder->category_id === null
            && $this->other_user_id === $user->id && $this->user_type === OrderUserType::BUYER) {
            $add_product = true;
            $can_cancelled = true;
        }

        if (($this->status === OrderStatus::WAIT || $this->status === OrderStatus::EDITED)
            && $this->damainOrder->category_id !== null
            && (($this->other_user_id === $user->id && $this->user_type === OrderUserType::SELLER)
                || ($this->user_id === $user->id && $this->user_type === OrderUserType::BUYER))) {
            $can_accept = true;
            $can_refuse = true;
        }

        if ($this->status === OrderStatus::REFUSED && $this->damainOrder->category_id !== null
            && (($this->other_user_id === $user->id && $this->user_type === OrderUserType::BUYER)
                || ($this->user_id === $user->id && $this->user_type === OrderUserType::SELLER))) {
            $can_edit = true;
            $can_cancelled = true;
        }

        if ($this->status === OrderStatus::ACCEPTED &&
            (($this->other_user_id === $user->id && $this->user_type === OrderUserType::SELLER)
                || ($this->user_id === $user->id && $this->user_type === OrderUserType::BUYER))) {
            $can_payment = true;
        }

        if ($this->status === OrderStatus::PROGRESS &&
            (($this->other_user_id === $user->id && $this->user_type === OrderUserType::BUYER)
                || ($this->user_id === $user->id && $this->user_type === OrderUserType::SELLER))) {
            $can_shipment = true;
        }

        if ($this->status === OrderStatus::SHIPPED && $this->shipment_type === ShipmentType::SELLER_SHIP
            && (($this->other_user_id === $user->id && $this->user_type === OrderUserType::BUYER)
                || ($this->user_id === $user->id && $this->user_type === OrderUserType::SELLER))) {
            $can_show_shipment_code = true;
        }

        if ($this->status === OrderStatus::SHIPPED &&
            (($this->other_user_id === $user->id && $this->user_type === OrderUserType::SELLER)
                || ($this->user_id === $user->id && $this->user_type === OrderUserType::BUYER))) {
            $can_accept_delivery = true;
            $can_refuse_delivery = true;
        }

        return [
            'add_product' => $add_product,
            'can_accept' => $can_accept,
            'can_refuse' => $can_refuse,
            'can_edit' => $can_edit,
            'can_cancelled' => $can_cancelled,
            'can_payment' => $can_payment,
            'can_shipment' => $can_shipment,
            'can_show_shipment_code' => $can_show_shipment_code,
            'can_accept_delivery' => $can_accept_delivery,
            'can_refuse_delivery' => $can_refuse_delivery,
            'can_accept_refuse_offers' => false
        ];
    }

    public function directActions()
    {
        $can_accept = false;
        $can_refuse = false;
        $can_payment = false;
        $can_shipment = false;
        $can_show_shipment_code = false;
        $can_accept_delivery = false;
        $can_refuse_delivery = false;

        $user = auth()->user();
        if (($this->status === OrderStatus::WAIT) && ($this->other_user_id === $user->id)) {
            $can_accept = true;
            $can_refuse = true;
        }
        if (($this->status === OrderStatus::ACCEPTED) && ($this->user_id === $user->id)) {
            $can_payment = true;
        }
        if (($this->status === OrderStatus::PROGRESS) && ($this->other_user_id === $user->id)) {
            $can_shipment = true;
        }
        if ($this->status === OrderStatus::SHIPPED && $this->shipment_type === ShipmentType::SELLER_SHIP
            && ($this->other_user_id === $user->id)) {
            $can_show_shipment_code = true;
        }
        if ($this->status === OrderStatus::SHIPPED && ($this->user_id === $user->id)) {
            $can_accept_delivery = true;
            $can_refuse_delivery = true;
        }

        return [
            'can_accept' => $can_accept,
            'can_refuse' => $can_refuse,
            'can_payment' => $can_payment,
            'can_shipment' => $can_shipment,
            'can_show_shipment_code' => $can_show_shipment_code,
            'can_accept_delivery' => $can_accept_delivery,
            'can_refuse_delivery' => $can_refuse_delivery,
            'add_product' => false,
            'can_edit' => false,
            'can_cancelled' => false,
            'can_accept_refuse_offers' => false
        ];
    }

    public function negotiationActions()
    {
        $can_accept = false;
        $can_refuse = false;
        $can_payment = false;
        $can_shipment = false;
        $can_show_shipment_code = false;
        $can_accept_delivery = false;
        $can_refuse_delivery = false;

        $can_accept_refuse_offers = false;

        $user = auth()->user();
        if (($this->status === OrderStatus::WAIT) && ($this->other_user_id === $user->id)) {
            $can_accept_refuse_offers = true;
        }
        if (($this->status === OrderStatus::ACCEPTED) && ($this->user_id === $user->id)) {
            $can_payment = true;
        }
        if (($this->status === OrderStatus::PROGRESS) && ($this->other_user_id === $user->id)) {
            $can_shipment = true;
        }
        if ($this->status === OrderStatus::SHIPPED && $this->shipment_type === ShipmentType::SELLER_SHIP
            && ($this->other_user_id === $user->id)) {
            $can_show_shipment_code = true;
        }
        if ($this->status === OrderStatus::SHIPPED && ($this->user_id === $user->id)) {
            $can_accept_delivery = true;
            $can_refuse_delivery = true;
        }

        return [
            'can_accept_refuse_offers' => $can_accept_refuse_offers,
            'can_accept' => $can_accept,
            'can_refuse' => $can_refuse,
            'can_payment' => $can_payment,
            'can_shipment' => $can_shipment,
            'can_show_shipment_code' => $can_show_shipment_code,
            'can_accept_delivery' => $can_accept_delivery,
            'can_refuse_delivery' => $can_refuse_delivery,
            'add_product' => false,
            'can_edit' => false,
            'can_cancelled' => false
        ];
    }

    public function bidActions()
    {
        $can_accept = false;
        $can_refuse = false;
        $can_payment = false;
        $can_shipment = false;
        $can_show_shipment_code = false;
        $can_accept_delivery = false;
        $can_refuse_delivery = false;
        $can_accept_refuse_offers = false;

        $user = auth()->user();
        if (($this->status === OrderStatus::WAIT) && ($this->other_user_id === $user->id)) {
            $can_accept_refuse_offers = true;
        }
        if (($this->status === OrderStatus::ACCEPTED) && ($this->user_id === $user->id)) {
            $can_payment = true;
        }
        if (($this->status === OrderStatus::PROGRESS) && ($this->other_user_id === $user->id)) {
            $can_shipment = true;
        }
        if ($this->status === OrderStatus::SHIPPED && $this->shipment_type === ShipmentType::SELLER_SHIP
            && ($this->other_user_id === $user->id)) {
            $can_show_shipment_code = true;
        }
        if ($this->status === OrderStatus::SHIPPED && ($this->user_id === $user->id)) {
            $can_accept_delivery = true;
            $can_refuse_delivery = true;
        }

        return [
            'can_accept_refuse_offers' => $can_accept_refuse_offers,
            'can_accept' => $can_accept,
            'can_refuse' => $can_refuse,
            'can_payment' => $can_payment,
            'can_shipment' => $can_shipment,
            'can_show_shipment_code' => $can_show_shipment_code,
            'can_accept_delivery' => $can_accept_delivery,
            'can_refuse_delivery' => $can_refuse_delivery,
            'add_product' => false,
            'can_edit' => false,
            'can_cancelled' => false
        ];
    }

    public function actions()
    {
        if ($this->type === OrderType::DAMAIN) {
            return $this->damainActions();
        } else if ($this->type === OrderType::DIRECT) {
            return $this->directActions();
        } else if ($this->type === OrderType::NEGOTIATION) {
            return $this->negotiationActions();
        } else if ($this->type === OrderType::BID) {
            return $this->bidActions();
        }
        return [];
    }
}
