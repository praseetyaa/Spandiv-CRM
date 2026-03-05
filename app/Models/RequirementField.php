<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequirementField extends Model
{
    use HasFactory;
    use \App\Traits\BelongsToCompany;

    protected $fillable = [
        'company_id',
        'service_category',
        'field_label',
        'field_name',
        'field_type',
        'field_options',
        'placeholder',
        'is_required',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'field_options' => 'array',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ── Scopes ──────────────────────────────────────────

    public function scopeForCategory($query, string $category)
    {
        return $query->whereIn('service_category', [$category, 'all']);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    // ── Helpers ──────────────────────────────────────────

    /**
     * Get all fields for a given service category, active and ordered.
     */
    public static function getFieldsForCategory(string $category)
    {
        return static::active()
            ->forCategory($category)
            ->ordered()
            ->get();
    }

    /**
     * Check if this field type supports options (select, checkbox, radio).
     */
    public function hasOptions(): bool
    {
        return in_array($this->field_type, ['select', 'checkbox', 'radio']);
    }
}
