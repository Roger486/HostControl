<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ReservationService extends Pivot
{
    protected $table = 'reservation_service';

    protected $fillable = [
        'reservation_id',
        'service_id',
        'amount',
    ];

    public $timestamps = true;
}
