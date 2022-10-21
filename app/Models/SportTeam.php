<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SportTeam extends Model
{
    use HasFactory;

    protected $table = 'sport_teams';
    protected $fillable = ['sport_id', 'team_id', 'name_ar', 'name_en', 'image'];

    public function getImageUrlAttribute()
    {
        $image = $this->image ? $this->image : 'images/default.png';
        return url($image);
    }
}
