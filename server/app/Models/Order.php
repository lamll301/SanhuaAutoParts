<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'voucher_id',
        'total_amount',
        'shipping_fee',
        'status',
        'name',
        'phone',
        'city_id',
        'district_id',
        'ward_id',
        'address_type',
        'shipping_address',
        'payment_method',
        'payment_status',
    ];
    
    protected $casts = [
        'total_amount' => 'integer',
        'shipping_fee' => 'integer',
    ];
    
    protected $appends = ['product_total'];

    public function getProductTotalAttribute()
    {
        return $this->details->sum(function ($detail) {
            return $detail->quantity * $detail->price;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}