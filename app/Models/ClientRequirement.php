<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'company_name',
        'phone',
        'email',
        'service_id',
        'budget_range',
        'requirements',
        'notes',
        'status',
        'lead_id',
        'ip_address',
    ];

    protected $casts = [
        'requirements' => 'array',
    ];

    // ── Relationships ───────────────────────────────────

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    // ── Scopes ──────────────────────────────────────────

    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    public function scopeConverted($query)
    {
        return $query->where('status', 'converted');
    }

    // ── Methods ─────────────────────────────────────────

    /**
     * Convert this requirement into a Lead.
     */
    public function convertToLead(): Lead
    {
        $requirementsSummary = '';
        if ($this->requirements && is_array($this->requirements)) {
            foreach ($this->requirements as $key => $value) {
                $label = str_replace('_', ' ', ucfirst($key));
                $val = is_array($value) ? implode(', ', $value) : $value;
                $requirementsSummary .= "• {$label}: {$val}\n";
            }
        }

        $notes = "📋 Dari Form Requirements #{$this->id}\n";
        $notes .= "Perusahaan: " . ($this->company_name ?: '-') . "\n";
        $notes .= "Budget: " . ($this->budget_range ?: '-') . "\n";
        if ($requirementsSummary) {
            $notes .= "\n--- Requirements ---\n{$requirementsSummary}";
        }
        if ($this->notes) {
            $notes .= "\n--- Catatan ---\n{$this->notes}";
        }

        $lead = Lead::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'source' => 'website',
            'service_id' => $this->service_id,
            'urgency_level' => 'medium',
            'status' => 'new',
            'notes' => $notes,
            'company_id' => $this->company_id,
        ]);

        $this->update([
            'status' => 'converted',
            'lead_id' => $lead->id,
        ]);

        return $lead;
    }

    public function isConverted(): bool
    {
        return $this->status === 'converted' && $this->lead_id !== null;
    }
}
