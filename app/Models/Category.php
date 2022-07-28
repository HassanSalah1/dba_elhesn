<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';
    protected $fillable = ['name_ar', 'name_en', 'category_id', 'fields'];

    protected $appends = ['all_fields'];

    public function getNameAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'en' ? $this->name_en : $this->name_ar;
    }

    public function getAllFieldsAttribute()
    {
        return !empty($this->fields) ?
            json_decode($this->fields, true) : [];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

}
