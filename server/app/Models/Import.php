<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Import extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'deliverer',
        'address',
        'import_date',
        // 'total_amount',
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function importDetails() {
        return $this->hasMany(ImportDetail::class);
    }
}
