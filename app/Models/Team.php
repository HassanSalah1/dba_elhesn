<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $table = 'teams';
    protected $fillable = ['title', 'name', 'position', 'image'];

    public function getImageUrlAttribute()
    {
        $image = $this->image ? $this->image : 'images/default.png';
        return url($image);
    }
}
