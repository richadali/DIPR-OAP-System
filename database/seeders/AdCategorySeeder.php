<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdCategory;

class AdCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdCategory::updateOrCreate(['category_name'=>'Display']);
        AdCategory::updateOrCreate(['category_name'=>'Classified']);
    }
}
 