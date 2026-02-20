<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectorAssignment extends Model
{
    protected $fillable = [
        'collector_id',
        'area_name',
        'start_date',
        'end_date',
    ];

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collector_id');
    }
}
