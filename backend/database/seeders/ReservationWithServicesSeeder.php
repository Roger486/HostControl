<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReservationWithServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 services
        $services = Service::factory(10)->create();

        // Get all reservations
        $reservations = Reservation::all();

        foreach ($reservations as $reservation) {
            // Attach 0 to 3 random services with 1 to 3 amount
            $randomServices = $services->random(rand(0, 3));
            foreach ($randomServices as $service) {
                $reservation->services()->attach($service->id, [
                    'amount' => rand(1, 3),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
    }
}
