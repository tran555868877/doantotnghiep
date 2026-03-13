<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BehaviorEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'category_id',
        'session_id',
        'event_type',
        'source',
        'search_keyword',
        'event_value',
        'meta',
        'ip_address',
        'user_agent',
        'occurred_at',
    ];

    protected function casts(): array
    {
        return [
            'occurred_at' => 'datetime',
        ];
    }
}
