<?php

namespace Database\Seeders;

use App\Models\Companion;
use App\Models\Reservation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gets all the reservations
        $allReservations = Reservation::with('accommodation')->get();
        //with('accomodation')->get(); instead of all(); for performance (less DB accesses)

        // Gives every row a random amount of companions (taking into acount the accomodation capacity)
        $allReservations->each(function ($reservation) {
            $accommodationCapacity = $reservation->accommodation?->capacity ?? 1;
            for ($i = 0; $i < random_int(0, $accommodationCapacity - 1); $i++) {
                Companion::factory()->create(['reservation_id' => $reservation->id]);
            }
        });
    }
}
