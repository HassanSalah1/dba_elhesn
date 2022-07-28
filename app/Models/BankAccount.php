<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'bank_accounts';

    protected $fillable = ['name_ar', 'name_en', 'bank_name', 'account_number', 'user_id', 'ipan'];

    public function getNameAttribute()
    {
        $locale = App::getLocale();
        return $locale === 'en' ? $this->name_en : $this->name_ar;
    }
}
