<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'highlight',
        'author',
        'publish_date',
        'content',
        'status',
    ];
    
    protected static function booted()
    {
        static::creating(function ($article) {
            $article->slug = $article->generateUniqueSlug($article->title);
        });

        static::updating(function ($article) {
            if ($article->isDirty('title')) {
                $article->slug = $article->generateUniqueSlug($article->title);
            }
        });
    }

    public function generateUniqueSlug($title) {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $count = 1;
        while (static::where('slug', $slug)
        ->where('id', '!=', $this->id ?? 0)
        ->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        return $slug;
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
