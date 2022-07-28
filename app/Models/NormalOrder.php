<?php

namespace App\Models;

use App\Entities\Key;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NormalOrder extends Model
{
    use HasFactory;

    protected $table = 'normal_orders';
    protected $fillable = ['order_id', 'product_id', 'fields', 'price'];

    protected $appends = ['all_fields'];

    public function getAllFieldsAttribute()
    {
        return !empty($this->fields) ?
            json_decode($this->fields, true) : [];
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
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
