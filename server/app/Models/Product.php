<?php

namespace App\Models;

use App\Traits\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes, HasSlug;

    protected $fillable = [
        'name',
        'description',
        'slug',
        'original_price',
        // 'price',
        // 'quantity',
        'dimensions',
        'weight',
        'color',
        'material',
        'compatibility',
        'status',
        'promotion_id',
        'unit_id',
        'supplier_id',
    ];

    protected $appends = ['sold', 'rate', 'reviewCount'];

    public function getSoldAttribute() 
    {
        return rand(10, 500);
    }

    public function getRateAttribute() 
    {
        $averageRating = $this->reviews()->avg('rating');
        return $averageRating ? round($averageRating, 1) : 0;
    }

    public function getReviewCountAttribute() 
    {
        return $this->reviews()->count();
    }

    protected function slugSourceField(): string
    {
        return 'name';
    }
    
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function unit() 
    {
        return $this->belongsTo(Unit::class);
    }

    public function supplier() 
    {
        return $this->belongsTo(Supplier::class);
    }

    public function promotion() 
    {
        return $this->belongsTo(Promotion::class);
    }

    public function categories() 
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }
}
