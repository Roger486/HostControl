<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'daily_price' ,
        'available_slots',
        'comments',
        'available_until',
        'scheduled_at',
        'ends_at'
    ];

    protected $casts = [
        'available_until' => 'datetime',
        'scheduled_at' => 'datetime',
        'ends_at' => 'datetime'
    ];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_service')
            ->using(ReservationService::class)
            ->withPivot('amount')
            ->withTimestamps();
    }
}
