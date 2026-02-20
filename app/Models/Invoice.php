<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'payer_id',
        'revenue_type_id',
        'amount',
        'due_date',
        'status',
        'created_by',
    ];

    public function payer(): BelongsTo
    {
        return $this->belongsTo(Payer::class);
    }

    public function revenueType(): BelongsTo
    {
        return $this->belongsTo(RevenueType::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function totalPaid(): float
    {
        return (float) $this->payments()->sum('amount_paid');
    }

    public function outstandingBalance(): float
    {
        return max((float) $this->amount - $this->totalPaid(), 0);
    }

    public function overpaymentAmount(): float
    {
        return max($this->totalPaid() - (float) $this->amount, 0);
    }

}
