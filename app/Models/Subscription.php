<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;
    use \App\Traits\BelongsToCompany;

    protected $fillable = [
        'client_id',
        'service_id',
        'start_date',
        'end_date',
        'billing_cycle',
        'price',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePaused($query)
    {
        return $query->where('status', 'paused');
    }

    public function needsInvoice(): bool
    {
        if ($this->status !== 'active')
            return false;

        $lastInvoice = $this->invoices()->latest('issue_date')->first();

        if (!$lastInvoice)
            return true;

        return $lastInvoice->issue_date->addDays(30)->lte(now());
    }
}
