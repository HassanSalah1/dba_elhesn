<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'contacts';
    protected $fillable = ['contact_type_id' , 'user_id' , 'message' , 'replay' , 'read'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function contactType() {
        return $this->belongsTo(ContactType::class);
    }
}
