<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'value',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'status',
    ];
}
