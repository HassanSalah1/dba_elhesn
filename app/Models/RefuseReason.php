<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefuseReason extends Model
{
    use HasFactory;

    protected $table = 'refuse_reasons';
    protected $fillable = ['reason' , 'order_id'];

    public function images() {
        return $this->hasMany(Image::class , 'refuse_request_id' , 'id');
    }
}
