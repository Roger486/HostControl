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
        'available_slots',
        'comments'
    ];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'reservation_service')
            ->using(ReservationService::class)
            ->withPivot('amount')
            ->withTimestamps();
    }
}
