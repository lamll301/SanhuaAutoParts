<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExportDetail extends Model
{
    protected $fillable = [
        'export_id',
        'product_id',
        'inventory_id',
        'quantity',
        'price',
    ];
}
