<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'location',
        'batch_number',
        'expiry_date',
        'manufacture_date',
        'quantity',
        'product_id',
        'import_id'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function import() {
        return $this->belongsTo(Import::class);
    }
}
