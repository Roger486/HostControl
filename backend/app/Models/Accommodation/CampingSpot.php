<?php

namespace App\Models\Accommodation;

use App\Models\Accommodation\Contracts\HasDynamicValidation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampingSpot extends Model implements HasDynamicValidation
{
    use HasFactory;

    protected $fillable = [
        'accommodation_id',
        'area_size_m2',
        'has_electricity',
        'accepts_caravan'
    ];

    protected $casts = [
        'area_size_m2' => 'integer',
        'has_electricity' => 'boolean',
        'accepts_caravan' => 'boolean'
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
     * Validation rules for creating or updating a camping spot.
     *
     * @param string $operation The validation context: 'store' or 'update'
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public static function rules(string $operation = 'store'): array
    {

        if ($operation === 'update') {
            return [
                'area_size_m2' => ['sometimes', 'required', 'integer', 'min:0'],
                'has_electricity' => ['sometimes', 'required', 'boolean'],
                'accepts_caravan' => ['sometimes', 'required', 'boolean']
            ];
        }

        return [
            'area_size_m2' => ['required', 'integer', 'min:1'],
            'has_electricity' => ['required', 'boolean'],
            'accepts_caravan' => ['required', 'boolean']
        ];
    }
}
