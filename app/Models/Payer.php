<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payer extends Model
{
    protected $fillable = [
        'payer_type',
        'full_name',
        'business_name',
        'phone',
        'email',
        'location',
        'electoral_area',
        'property_number',
        'business_type',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

}
