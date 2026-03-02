<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'amount',
        'method',
        'payment_date',
        'proof_file',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    protected static function booted()
    {
        static::created(function (Payment $payment) {
            $payment->invoice->recalculateStatus();

            // Update client lifetime value if invoice is fully paid
            if ($payment->invoice->status === 'paid') {
                $payment->invoice->client->recalculateLifetimeValue();
            }
        });
    }
}
