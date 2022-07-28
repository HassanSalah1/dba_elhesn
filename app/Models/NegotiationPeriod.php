<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NegotiationPeriod extends Model
{
    use HasFactory;

    protected $table = 'negotiation_periods';
    protected $fillable = ['period' , 'type'];
}
