<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class PaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payment_methods';
    protected $fillable = ['name_ar', 'name_en'];

    public function getNameAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'en' ? $this->name_en : $this->name_ar;
    }
}
