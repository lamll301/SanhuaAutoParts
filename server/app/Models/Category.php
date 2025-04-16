<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'type',
    ];

    protected function slugSourceField(): string {
        return 'name';
    }

    public function images(): HasMany {
        return $this->hasMany(Image::class);
    }
}
