<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueType extends Model
{
    protected $fillable = [
        'name',
        'category',
        'default_amount',
        'frequency',
        'description',
        'status',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

}
