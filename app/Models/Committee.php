<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    use HasFactory;
    protected $table = 'committees';
    protected $fillable = ['name_ar' , 'name_en' , 'description_ar' , 'description_en'];
}
