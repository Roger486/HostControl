<?php

namespace App\Models\Accommodation;

use App\Models\Accommodation\Contracts\HasDynamicValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class House extends Model implements HasDynamicValidation
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'bed_amount',
        'room_amount',
        'has_air_conditioning'
    ];

    protected $casts = [
        'bed_amount' => 'integer',
        'room_amount' => 'integer',
        'has_air_conditioning' => 'boolean'
    ];

    /**
     * Get the associated base accommodation.
     *
     * @return BelongsTo
     */
    public function accommodation(): BelongsTo
    {
        return $this->belongsTo(Accommodation::class, 'accommodation_id');
    }

    /**
     * Validation rules for creating or updating a house.
     *
     * @param string $operation The context: 'store' or 'update'
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public static function rules(string $operation = 'store'): array
    {

        if ($operation === 'update') {
            return [
                'bed_amount' => ['sometimes', 'required', 'integer', 'min:0'],
                'room_amount' => ['sometimes', 'required', 'integer', 'min:0'],
                'has_air_conditioning' => ['sometimes', 'required', 'boolean']
            ];
        }

        return [
            'bed_amount' => ['required', 'integer', 'min:0'],
            'room_amount' => ['required', 'integer', 'min:0'],
            'has_air_conditioning' => ['required', 'boolean']
        ];
    }
}
