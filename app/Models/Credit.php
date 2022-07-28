<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $table = 'credits';
    protected $fillable = ['user_id', 'order_id', 'amount', 'type', 'payment_method_id', 'bank_transfer_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
