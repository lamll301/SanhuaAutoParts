<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;

    const STATUS_INACTIVE = 0;  // chua kich hoat (ban nhap)
    const STATUS_ACTIVE = 1;    // dang ap dung
    const STATUS_SCHEDULED = 2; // len lich (chua den thoi gian bat dau)
    const STATUS_EXPIRED = 3;   // het han
    const STATUS_CANCELLED = 4; // da huy (da bat dau nhung khong ap dung nua)
    
    /* Luong su kien: 
        0 => 2 => 1 => 3
        2 + => 4
    */

    protected $fillable = [
        'name',
        'discount_percent',
        'max_discount_amount',
        'start_date',
        'end_date',
        'status'
    ];
}
