<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'status',
        'latitude',
        'longitude',
        'address',
        'image',
        'reported_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function statusHistory(): HasMany
    {
        return $this->hasMany(StatusHistory::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
