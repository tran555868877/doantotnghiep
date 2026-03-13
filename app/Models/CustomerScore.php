<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'favorite_category_id',
        'engagement_score',
        'purchase_score',
        'retention_probability',
        'segment',
        'recommended_product_ids',
        'calculated_at',
    ];

    protected function casts(): array
    {
        return [
            'calculated_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favoriteCategory()
    {
        return $this->belongsTo(Category::class, 'favorite_category_id');
    }

    public function recommendedProductIds(): array
    {
        if (! $this->recommended_product_ids) {
            return [];
        }

        return array_values(array_filter(array_map('intval', explode(',', $this->recommended_product_ids))));
    }
}
