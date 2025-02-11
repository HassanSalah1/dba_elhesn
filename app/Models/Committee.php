<?php

namespace App\Models;

use App\Entities\ImageType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class Committee extends Model
{
    use HasFactory;

    protected $table = 'committees';
    protected $fillable = ['name_ar', 'name_en', 'description_ar', 'description_en', 'image' , 'order'];
    protected $appends = ['name', 'description'];

    public function getImageUrlAttribute()
    {
        $image = $this->image;
        return $image ? url($image) : null;
    }

    public function getNameAttribute()
    {
        $lang = App::getLocale();
        return $lang === 'en' ? $this->name_en : $this->name_ar;
    }

    public function getDescriptionAttribute()
    {
        $lang = App::getLocale();
        return $lang === 'en' ? $this->description_en : $this->description_ar;
    }

}
