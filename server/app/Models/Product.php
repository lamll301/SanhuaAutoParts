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

    protected function slugSourceField(): string {
        return 'name';
    }
    
    public function images(): HasMany {
        return $this->hasMany(Image::class);
    }

    public function unit() {
        return $this->belongsTo(Unit::class);
    }

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'product_category');
    }
}
