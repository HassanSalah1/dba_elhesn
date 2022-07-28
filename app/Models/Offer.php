<?php

namespace App\Models;

use App\Entities\OfferStatus;
use App\Entities\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $table = 'offers';
    protected $fillable = ['product_id', 'order_id', 'user_id', 'price', 'status'];

    protected $casts = [
        'product_id' => 'integer',
        'order_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actions()
    {
        $user = auth()->user();
        $can_accept = false;
        $can_refuse = false;
        $order = $this->order;
        if ($user->id === $this->product->user_id && $this->status === OfferStatus::NEW
            && $order->status === OrderStatus::WAIT) {
            $can_accept = true;
            $can_refuse = true;
        }
        return [
            'can_accept' => $can_accept,
            'can_refuse' => $can_refuse,
        ];
    }
}
