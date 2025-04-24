<?php

namespace Database\Seeders;

use App\Models\Accommodation\Accommodation;
use App\Models\Accommodation\AccommodationImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AccommodationImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Iterate throught each accommodation
        Accommodation::all()->each(function ($accommodation) {
            // determine the folder name depending on the accommodation subtype
            $imageFolder = public_path('demo-images/' . $accommodation->type); // ie: 'bungalow', 'house', etc.

            if (File::exists($imageFolder)) {
                // get all files in the folder
                $files = File::allFiles($imageFolder);
                // create the AccommodationImage's
                foreach ($files as $file) {
                    AccommodationImage::create([
                        'accommodation_id' => $accommodation->id,
                        'image_path' => 'demo-images/' . $accommodation->type . '/' . $file->getFilename(),
                    ]);
                }
            }
        });
    }
}
