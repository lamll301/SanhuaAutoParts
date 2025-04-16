<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Export extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'export_date',
        'receiver',
        'address',
        'reason',
        'total_amount',
    ];
}
