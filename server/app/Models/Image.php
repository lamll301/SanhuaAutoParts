<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'filename',
        'path',
        'size',
        'mime_type',
        'is_thumbnail',
        'category_id',
        'article_id',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
