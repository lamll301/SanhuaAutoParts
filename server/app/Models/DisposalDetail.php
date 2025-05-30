<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisposalDetail extends Model
{
    protected $fillable = [
        'disposal_id', 'inventory_id', 'quantity', 'price', 'method'
    ];

    public function disposal(): BelongsTo
    {
        return $this->belongsTo(Disposal::class);
    }

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }
}
