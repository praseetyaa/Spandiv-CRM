<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'source',
        'service_id',
        'estimated_value',
        'urgency_level',
        'lead_score',
        'status',
        'last_follow_up',
        'notes',
    ];

    protected $casts = [
        'estimated_value' => 'decimal:2',
        'lead_score' => 'integer',
        'last_follow_up' => 'datetime',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeWon($query)
    {
        return $query->where('status', 'closed_won');
    }

    public function scopeLost($query)
    {
        return $query->where('status', 'closed_lost');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['closed_won', 'closed_lost']);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeHighUrgency($query)
    {
        return $query->where('urgency_level', 'high');
    }

    public function calculateLeadScore(): int
    {
        $score = 0;

        // Urgency scoring
        $score += match ($this->urgency_level) {
            'high' => 30,
            'medium' => 15,
            'low' => 5,
        };

        // Value scoring
        if ($this->estimated_value) {
            if ($this->estimated_value >= 10000000)
                $score += 30;
            elseif ($this->estimated_value >= 5000000)
                $score += 20;
            elseif ($this->estimated_value >= 1000000)
                $score += 10;
            else
                $score += 5;
        }

        // Status scoring
        $score += match ($this->status) {
            'negotiation' => 25,
            'proposal_sent' => 15,
            'contacted' => 10,
            'new' => 5,
            default => 0,
        };

        // Follow-up recency
        if ($this->last_follow_up && $this->last_follow_up->diffInDays(now()) <= 3) {
            $score += 10;
        }

        return min($score, 100);
    }
}
