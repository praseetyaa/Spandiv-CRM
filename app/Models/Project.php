<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'service_id',
        'title',
        'price',
        'start_date',
        'deadline',
        'status',
        'progress_percentage',
        'description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'start_date' => 'date',
        'deadline' => 'date',
        'progress_percentage' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['completed']);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('deadline', '<', now())
            ->where('status', '!=', 'completed');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function calculateProgressFromMilestones(): int
    {
        $total = $this->milestones()->count();
        if ($total === 0)
            return 0;

        $done = $this->milestones()->where('status', 'done')->count();
        return (int) round(($done / $total) * 100);
    }
}
