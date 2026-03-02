<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'category',
        'base_price',
        'billing_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    public function scopeRecurring($query)
    {
        return $query->where('billing_type', 'recurring');
    }

    public function scopeOneTime($query)
    {
        return $query->where('billing_type', 'one_time');
    }
}
