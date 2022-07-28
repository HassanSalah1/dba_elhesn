<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['name', 'description', 'category_id', 'sub_category_id'
        , 'sub_sub_category_id', 'price', 'max_price', 'show_user', 'negotiation',
        'user_id', 'percent', 'period', 'period_type', 'type', 'fields'];

    protected $casts = [
        'price' => 'decimal:2',
        'max_price' => 'decimal:2',
        'percent' => 'decimal:2'
    ];

    protected $appends = ['all_fields'];

    public function getAllFieldsAttribute()
    {
        return !empty($this->fields) ?
            json_decode($this->fields, true) : [];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function normalOrder()
    {
        return $this->hasOne(NormalOrder::class, 'product_id', 'id');
    }


    public function chat()
    {
        return $this->hasOne(Chat::class, 'product_id', 'id')
            ->where(function ($query) {
                $query->where(['user_id' => auth()->id()])
                    ->orWhere(['owner_id' => auth()->id()]);
            })->first();
    }

    public function image()
    {
        return $this->hasMany(ProductImage::class)->orderBy('id', 'DESC')
            ->first();
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function comments()
    {
        return $this->hasMany(ProductComment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->withTrashed();
    }

    public function sub_category()
    {
        return $this->belongsTo(Category::class, 'sub_category_id', 'id')->withTrashed();
    }

    public function sub_sub_category()
    {
        return $this->belongsTo(Category::class, 'sub_sub_category_id', 'id')->withTrashed();
    }


}
