<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promotion extends Model
{
    use SoftDeletes;

    const STATUS_SCHEDULED = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_EXPIRED = 2;

    protected $fillable = [
        'name',
        'discount_percent',
        'max_discount_amount',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'status' => 'integer',
        'discount_percent' => 'integer',
        'max_discount_amount' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
