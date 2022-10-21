<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamPlayer extends Model
{
    use HasFactory;

    protected $table = 'team_players';
    protected $fillable = ['team_id', 'player_id', 'name_en', 'name_ar', 'image'];

    public function getImageUrlAttribute()
    {
        $image = $this->image ? $this->image : 'images/default.png';
        return url($image);
    }
}
