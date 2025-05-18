<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherUsage extends Model
{
    protected $fillable = [
        'user_id',
        'voucher_id',
        'order_id',
    ];
}
