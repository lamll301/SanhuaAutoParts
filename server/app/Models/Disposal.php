<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Disposal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by', 'approved_by', 'date', 'reason', 'total_amount'
    ];

    protected $casts = [
        'date' => 'date:Y-m-d',
        'status' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function details(): HasMany
    {
        return $this->hasMany(DisposalDetail::class);
    }
}

