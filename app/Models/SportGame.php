<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class SportGame extends Model
{
    use HasFactory;
    protected $table = 'sport_games';
    protected $fillable = ['title_ar' , 'title_en'  , 'description_ar' , 'description_en' , 'image' , 'order'];

    public function getImageUrlAttribute()
    {
        $image = $this->image;
        return $image ? url($image) : null;
    }

    public function getTitleAttribute()
    {
        $lang = App::getLocale();
        return $lang === 'en' ? $this->title_en : $this->title_ar;
    }

    public function getDescriptionAttribute()
    {
        $lang = App::getLocale();
        return $lang === 'en' ? $this->description_en : $this->description_ar;
    }
}
