<?php

namespace App\Models;

use App\Entities\Key;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamainOrder extends Model
{
    use HasFactory;

    protected $table = 'damain_orders';

    protected $fillable = ['order_id', 'category_id'
        , 'sub_category_id', 'sub_sub_category_id', 'name', 'description', 'price'
        , 'latitude', 'longitude', 'address', 'user_name', 'phone', 'phonecode'];

    protected $casts = [
        'price' => 'decimal:2',
        'category_id' => 'integer',
        'sub_category_id' => 'integer',
        'sub_sub_category_id' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->withTrashed();
    }

    public function sub_category()
    {
        return $this->belongsTo(Category::class, 'sub_category_id', 'id')->withTrashed();
    }

    public function sub_sub_category()
    {
        return $this->belongsTo(Category::class, 'sub_sub_category_id', 'id')->withTrashed();
    }

    public function calc_pricing()
    {
        $appPercentage = Setting::where(['key' => Key::APP_PERCENTAGE])->first();
        $percentValue = $this->price * ($appPercentage->value / 100);
        return [
            'price' => $this->price,
            'app_percent' => $appPercentage->value,
            'app_percent_value' => number_format($percentValue, 2),
            'total' => number_format($this->price + $percentValue, 2)
        ];
    }

    public function getTotalPriceAttribute()
    {
        $appPercentage = Setting::where(['key' => Key::APP_PERCENTAGE])->first();
        $percentValue = $this->price * ($appPercentage->value / 100);
        return floatval(number_format($this->price + $percentValue, 2));
    }

}
