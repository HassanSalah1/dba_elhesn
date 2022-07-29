<?php

namespace App\Models;

use App\Entities\ImageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';
    protected $fillable = ['title_ar', 'title_en', 'description_ar', 'description_en', 'video_url'];

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

    public function images()
    {
        return $this->hasMany(Image::class, 'item_id', 'id')
            ->where(['item_type' => ImageType::NEWS])->get();
    }

    public function image()
    {
        return $this->hasOne(Image::class, 'item_id', 'id')
            ->where(['item_type' => ImageType::NEWS])
            ->where(['primary' => 0])->first();
    }
}
