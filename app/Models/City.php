<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class City extends Model
{
    use HasFactory , SoftDeletes;

    protected $casts = [
        'status' => 'integer',
    ];

    public function getNameAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'en' ? $this->name_en : $this->name_ar;
    }

    public function country() {
        return $this->belongsTo(Country::class);
    }
}
