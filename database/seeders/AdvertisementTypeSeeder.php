<?php

namespace Database\Seeders;
use App\Models\AdvertisementType;

use Illuminate\Database\Seeder;

class AdvertisementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdvertisementType::create(['name' => 'Print']);
        AdvertisementType::create(['name' => 'Video']);
        AdvertisementType::create(['name' => 'Online Media']);
        AdvertisementType::create(['name' => 'Radio']);
        
    }
}
