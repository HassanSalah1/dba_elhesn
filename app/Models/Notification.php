<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = ['user_id', 'title_key', 'message_key', 'order_id', 'offer_id'
        , 'title_ar', 'title_en', 'message_en', 'message_ar', 'type'];

    public function getTitleAttribute()
    {
        return App::getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getMessageAttribute()
    {
        return App::getLocale() === 'ar' ? $this->message_ar : $this->message_en;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
