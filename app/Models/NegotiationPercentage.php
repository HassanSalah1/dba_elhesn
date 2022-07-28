<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegotiationPercentage extends Model
{
    use HasFactory;

    protected $table = 'negotiation_percentages';
    protected $fillable = ['percent'];
}
