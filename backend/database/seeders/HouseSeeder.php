<?php

namespace Database\Seeders;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\House;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gets all the Accommodations of CampingSpot type without a linked House
        $accommodationsWithoutCampingSpot = Accommodation::where('type', 'house')
        ->whereDoesntHave('House')->get();
        // Gives every row a linked House freshly created
        $accommodationsWithoutCampingSpot->each(function ($accommodation) {
            House::factory()->create(['accommodation_id' => $accommodation->id,]);
        });
    }
}
