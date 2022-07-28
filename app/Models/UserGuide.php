<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class UserGuide extends Model
{
    use HasFactory;

    protected $table = 'user_guides';
    protected $fillable = ['image', 'description_ar', 'description_en'];

    protected $appends = ['description'];

    public function getDescriptionAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'en' ? $this->description_en : $this->description_ar;
    }
}
