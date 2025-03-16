<?php

namespace Database\Seeders;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gets all the Accommodations of CampingSpot type without a linked Room
        $accommodationsWithoutCampingSpot = Accommodation::where('type', 'Room')
        ->whereDoesntHave('Room')->get();
        // Gives every row a linked Room freshly created
        $accommodationsWithoutCampingSpot->each(function ($accommodation) {
            Room::factory()->create(['accommodation_id' => $accommodation->id,]);
        });
    }
}
