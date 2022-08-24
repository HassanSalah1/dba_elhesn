<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intro extends Model
{
    use HasFactory;

    protected $table = 'intros';
    protected $fillable = ['title_ar', 'title_en', 'description_ar', 'description_en'];

    protected $appends = ['title', 'description'];

    public function getTitleAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'en' ? $this->title_en : $this->title_ar;
    }

    public function getDescriptionAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'en' ? $this->description_en : $this->description_ar;
    }
}
