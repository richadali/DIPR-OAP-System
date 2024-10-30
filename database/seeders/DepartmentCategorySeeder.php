<?php

namespace Database\Seeders;
use App\Models\DepartmentCategory;
use Illuminate\Database\Seeder;

class DepartmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            ['category_name' => 'State Govt'],
            ['category_name' => 'Central Govt'],
            ['category_name' => 'PSU'],
            ['category_name' => 'Agency/Org'],
        ];

        foreach ($categories as $category) {
            DepartmentCategory::create($category);
        }
    }

}