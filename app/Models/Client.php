<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'business_name',
        'industry',
        'instagram',
        'website',
        'total_lifetime_value',
        'client_status',
        'lead_id',
    ];

    protected $casts = [
        'total_lifetime_value' => 'decimal:2',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function scopeActive($query)
    {
        return $query->where('client_status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('client_status', 'inactive');
    }

    public function scopeByIndustry($query, string $industry)
    {
        return $query->where('industry', $industry);
    }

    public function recalculateLifetimeValue(): void
    {
        $this->total_lifetime_value = $this->invoices()
            ->where('status', 'paid')
            ->sum('total_amount');
        $this->save();
    }
}
