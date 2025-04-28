<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'highlight',
        'publish_date',
        'content',
        'author'
    ];

    protected $casts = [
        'publish_date' => 'date',
    ];
    
    protected function slugSourceField(): string 
    {
        return 'title';
    }

    public function images(): HasMany 
    {
        return $this->hasMany(Image::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
