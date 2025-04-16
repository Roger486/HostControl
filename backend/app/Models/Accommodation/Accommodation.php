<?php

namespace App\Models\Accommodation;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Accommodation extends Model
{
    use HasFactory;

    // Accommodation Types
    public const TYPE_HOUSE = 'house';
    public const TYPE_BUNGALOW = 'bungalow';
    public const TYPE_CAMPING_SPOT = 'camping_spot';
    public const TYPE_ROOM = 'room';

    /**
     * All type values should be written in lower_snake_case
     */
    public const TYPES = [
        self::TYPE_HOUSE,
        self::TYPE_BUNGALOW,
        self::TYPE_CAMPING_SPOT,
        self::TYPE_ROOM
    ];

    /**
     * Base attributes shared across all accommodation types.
     * Used to extract common fields during creation or updates.
     */
    public const BASE_ATTRIBUTES = [
        'accommodation_code',
        'section',
        'capacity',
        'price_per_day',
        'is_available',
        'comments',
        'type'
    ];

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
     * Return an array with the name of all relations with the correct lowerCamelCase format.
     * Since the array TYPES must be written in lower_snake_case,
     * we apply Str::camel to TYPES through array_map to make the conversion.
     */
    public static function withAllRelations()
    {
        return array_map([Str::class, 'camel'], self::TYPES);
        /*
        This sintax is also valid, but requires manual import of Str
        array_map('Str::camel', Accommodation::TYPES)
        */
    }

    /**
     * Scope to filter accommodations that are available (i.e., not reserved)
     * between two given dates.
     *
     * This scope excludes accommodations that have at least one reservation
     * overlapping with the given check-in and check-out dates.
     *
     * Optionally, it can ignore reservations with the status "cancelled".
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The current query builder instance.
     * @param \Carbon\Carbon $checkInDate The desired check-in date.
     * @param \Carbon\Carbon $checkOutDate The desired check-out date.
     * @param bool $ignoreCancelled Whether to exclude cancelled reservations (default: true).
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailableBetweenDates(
        Builder $query,
        Carbon $checkInDate,
        Carbon $checkOutDate,
        bool $ignoreCancelled = true
    ) {
        return $query->whereDoesntHave(
            'reservations',
            function ($subQuery) use ($checkInDate, $checkOutDate, $ignoreCancelled) {
                $subQuery->where('check_in_date', '<', $checkOutDate)
                    ->where('check_out_date', '>', $checkInDate);

                if ($ignoreCancelled) {
                    $subQuery->whereNot('status', Reservation::STATUS_CANCELLED);
                }
            }
        );
    }

    /**
     * Applies a set of filters to the Accommodation query.
     *
     * Supported filters:
     * - is_available: boolean
     * - type: string (must match one of the defined types)
     * - min_capacity: integer (minimum number of guests)
     * - max_capacity: integer (maximum number of guests)
     * - check_in_date and check_out_date: used together to filter by availability
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query to apply filters on.
     * @param array $filters The validated input filters from the request.
     *
     * @return void
     */
    public static function applyFilters(Builder $query, array $filters)
    {
        if (array_key_exists('is_available', $filters)) {
            $query->where('is_available', $filters['is_available']);
        }

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['min_capacity'])) {
            $query->where('capacity', '>=', $filters['min_capacity']);
        }

        if (isset($filters['max_capacity'])) {
            $query->where('capacity', '<=', $filters['max_capacity']);
        }

        if (isset($filters['check_in_date']) && isset($filters['check_out_date'])) {
            $checkInDate = Carbon::parse($filters['check_in_date']);
            $checkOutDate = Carbon::parse($filters['check_out_date']);

            $query->availableBetweenDates($checkInDate, $checkOutDate);
        }
    }
}
