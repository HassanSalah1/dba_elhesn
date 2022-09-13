<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class Category extends Model
{
    use HasFactory , SoftDeletes;
    protected $table = 'categories';
    protected $fillable = ['name_ar' , 'name_en'];
    public function getNameAttribute()
    {
        $lang = App::getLocale();
        return $lang === 'en' ? $this->name_en : $this->name_ar;
    }
}
