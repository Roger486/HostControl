<?php

namespace App\Models;

// TODO: implement email verification
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    // Document Types
    public const DOCUMENT_DNI = 'DNI';
    public const DOCUMENT_NIE = 'NIE';
    public const DOCUMENT_PASSPORT = 'Passport';

    public const DOCUMENT_TYPES = [
        self::DOCUMENT_DNI,
        self::DOCUMENT_NIE,
        self::DOCUMENT_PASSPORT
    ];

    // User Roles
    public const ROLE_USER = 'user';
    public const ROLE_ADMIN = 'admin';

    public const ROLES = [
        self::ROLE_USER,
        self::ROLE_ADMIN
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name_1',
        'last_name_2',
        'email',
        'password',
        'birthdate',
        'address',
        'document_type',
        'document_number',
        'phone',
        'role',
        'comments'
    ];

    /**
     * These attributes will not be included when converting the model to JSON or an array.
     *
     * This helps to keep sensitive data (like passwords) hidden in API responses.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast attributes to native types.
     *
     * This ensures that certain fields are automatically converted to the right type.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // Carbon object
            'birthdate' => 'date', // Carbon object with only the date
            'password' => 'hashed', // Hashes any password when stored (irreversible)
            'role' => 'string', // Ensures the Enum `role` is always stored and retrieved as a string
            'document_type' => 'string' // Ensures the Enum `document_type` is always stored and retrieved as a string
        ];
    }

    // ========================
    //      RELATIONSHIPS
    // ========================

    /**
     * Get all reservations where the user is the one who made the booking.
     *
     * This retrieves all reservations where the `booked_by_id`
     * in the `reservations` table matches this user's `id`.
     *
     * Equivalent SQL query:
     * SELECT * FROM reservations WHERE booked_by_id = this->id;
     *
     * @return HasMany
     */
    public function bookedByReservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'booked_by_id');
    }

    /**
     * Get all reservations where this user is the main guest.
     *
     * This retrieves all reservations where the `guest_id`
     * in the `reservations` table matches this user's `id`.
     *
     * Equivalent SQL query:
     * SELECT * FROM reservations WHERE guest_id = this->id;
     *
     * @return HasMany
     */
    public function guestReservations(): HasMany
    {
        return $this->hasMany(Reservation::class, 'guest_id');
    }

    /**
     * Get all reservation log actions performed by this user.
     *
     * This retrieves all logs where the `user_id`
     * in the `reservation_logs` table matches this user's `id`.
     *
     * Equivalent SQL query:
     * SELECT * FROM reservation_logs WHERE user_id = this->id;
     *
     * @return HasMany
     */
    public function reservationLogs(): HasMany
    {
        return $this->hasMany(ReservationLog::class, 'user_id');
    }
}
