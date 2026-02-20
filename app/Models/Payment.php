<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'receipt_number',
        'invoice_id',
        'amount_paid',
        'payment_method',
        'collector_id',
        'received_by',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function collector()
    {
        return $this->belongsTo(User::class, 'collector_id');
    }

}
