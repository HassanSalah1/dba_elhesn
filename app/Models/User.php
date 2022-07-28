<?php

namespace App\Models;

use App\Entities\CreditType;
use App\Entities\OrderStatus;
use App\Entities\Status;
use App\Entities\UserRoles;
use App\Entities\WithdrawRequestStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'phonecode', 'email', 'password', 'city_id', 'address', 'latitude',
        'longitude', 'device_token', 'device_type', 'role', 'status', 'lang',
        'image', 'edit_phone', 'edit_phonecode'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'integer',
        'city_id' => 'integer',
        'latitude' => 'double',
        'longitude' => 'double',
    ];

    public function getFullPhoneAttribute()
    {
        return $this->phonecode . $this->phone;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? url($this->image, [], env('APP_ENV') === 'local' ? false : true) : null;
    }

    public function scopeEnabled($query)
    {
        return $query->where('status', Status::ACTIVE);
    }

    function isDashboardAuth()
    {
        if ($this->role === UserRoles::ADMIN) {
            return true;
        }
        return false;
    }

    function isCustomerAuth()
    {
        if ($this->role === UserRoles::CUSTOMER) {
            return true;
        }
        return false;
    }


    function isActiveCustomerAuth()
    {
        if ($this->role === UserRoles::CUSTOMER && $this->status === Status::ACTIVE) {
            return true;
        }
        return false;
    }

    function isActiveUser()
    {
        if ($this->status === Status::ACTIVE) {
            return true;
        }
        return false;
    }

    function isBlocked()
    {
        if ($this->status === Status::INACTIVE) {
            return true;
        }
        return false;
    }

    function isNotPhoneVerified()
    {
        if ($this->status === Status::UNVERIFIED) {
            return true;
        }
        return false;
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function bankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function credit()
    {
        return $this->hasMany(Credit::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'id');
    }

    public function getBalanceAttribute()
    {
        $charge = $this->credit()->where(['type' => CreditType::CHARGE])->sum('amount');
        $sell = $this->credit()->where(['type' => CreditType::SELL])->sum('amount');

        $withdraw = $this->credit()->where(['type' => CreditType::WITHDRAW])->sum('amount');
        $buy = $this->credit()->where(['type' => CreditType::BUY])->sum('amount');

        return ($charge + $sell) - ($withdraw + $buy);
    }

    public function getRealBalanceAttribute()
    {
        $withdrawRequestAmount = WithdrawRequest::where(['user_id' => $this->id, 'status' => WithdrawRequestStatus::WAIT])
            ->sum('amount');
        return $this->balance - $withdrawRequestAmount;
    }

    public function getTotalCompletedOrdersAttribute()
    {
        $buy = $this->credit()->whereHas('order', function ($query) {
            $query->where(['status' => OrderStatus::COMPLETED]);
        })->where(['type' => CreditType::BUY])->sum('amount');
        $sell = $this->credit()->whereHas('order', function ($query) {
            $query->where(['status' => OrderStatus::COMPLETED]);
        })->where(['type' => CreditType::SELL])->sum('amount');

        return $sell + $buy;
    }
}
