<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Article extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'highlight',
        'author',
        'publish_date',
        'content',
        'status',
    ];
    
    protected function slugSourceField(): string {
        return 'title';
    }

    public function images(): HasMany {
        return $this->hasMany(Image::class);
    }
}
