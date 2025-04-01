<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'discount_percent',
        'max_discount_amount',
        'start_date',
        'end_date',
        'status'
    ];
}
