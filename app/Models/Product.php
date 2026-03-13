<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'brand',
        'origin_country',
        'age_group',
        'unit',
        'price',
        'sale_price',
        'stock',
        'sold_count',
        'view_count',
        'rating',
        'thumbnail',
        'gallery',
        'short_description',
        'description',
        'attributes',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'attributes' => 'array',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFinalPriceAttribute(): float
    {
        return (float) ($this->sale_price ?: $this->price);
    }
}
