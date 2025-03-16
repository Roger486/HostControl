<?php

namespace App\Models\Accommodation;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Accommodation extends Model
{
    use HasFactory;

    protected $fillable = [
        'accommodation_code',
        'section',
        'capacity',
        'price_per_day',
        'is_available',
        'comments',
        'type'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'price_per_day' => 'integer',
        'is_available' => 'boolean',
        'type' => 'string' // Ensures the Enum `type` is always stored and retrieved as a string
    ];

    /**
     * Get all reservations linked to this accommodation.
     *
     * One accommodation can have multiple reservations.
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'accommodation_id');
    }

    /**
     * Get the Camping Spot associated with this Accommodation.
     *
     * One Accommodation can have at most one Camping Spot.
     */
    public function campingSpot(): HasOne
    {
        return $this->hasOne(CampingSpot::class, 'accommodation_id');
    }

    /**
     * Get the bungalow associated with this Accommodation.
     *
     * One Accommodation can have at most one bungalow.
     */
    public function bungalow(): HasOne
    {
        return $this->hasOne(Bungalow::class, 'accommodation_id');
    }

    /**
     * Get the house associated with this Accommodation.
     *
     * One Accommodation can have at most one house.
     */
    public function house(): HasOne
    {
        return $this->hasOne(House::class, 'accommodation_id');
    }

    /**
     * Get the room associated with this Accommodation.
     *
     * One Accommodation can have at most one room.
     */
    public function room(): HasOne
    {
        return $this->hasOne(Room::class, 'accommodation_id');
    }

    /**
     * Sets the `type` attribute automatically when creating an accommodation.
     *
     * The `type` field is an ENUM that stores the class name as a value.
     * This makes creating accommodations slightly slower, but searching by type much faster.
     */
    // protected static function boot()
    // {
    //     // Calls Laravel's default boot() to keep important Eloquent features working.
    //     parent::boot();

    //     static::creating(function ($accommodation) {
    //         $accommodation->type = class_basename($accommodation);
    //     });
    // }
}
