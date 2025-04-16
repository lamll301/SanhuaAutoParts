<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportDetail extends Model
{
    protected $fillable = [
        'import_id',
        'product_id',
        'quantity',
        'price',
    ];
}
