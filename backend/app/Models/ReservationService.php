<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ReservationService extends Pivot
{
    protected $table = 'reservation_service';

    protected $fillable = [
        'reservation_id',
        'service_id',
        'amount',
    ];

    /**
     * Enable timestamps (created_at and updated_at) for this pivot table.
     */
    public $timestamps = true;
}
