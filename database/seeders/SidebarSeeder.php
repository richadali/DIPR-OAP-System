<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sidebar;

class SidebarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sidebarData = [
            [
                'sidebar_name' => 'Home',
                'sidebar_inactive' => false,
                'sidebar_order' => 1,
            ],
            [
                'sidebar_name' => 'Master Data',
                'sidebar_inactive' => false,
                'sidebar_order' => 2,
            ],
            [
                'sidebar_name' => 'User Management',
                'sidebar_inactive' => false,
                'sidebar_order' => 3,
            ],
            [
                'sidebar_name' => 'Advertisements',
                'sidebar_inactive' => false,
                'sidebar_order' => 4,
            ],
            [
                'sidebar_name' => 'Reports',
                'sidebar_inactive' => false,
                'sidebar_order' => 5,
            ],
            [
                'sidebar_name' => 'logout',
                'sidebar_inactive' => false,
                'sidebar_order' => 6,
            ],
            [
                'sidebar_name' => 'Bill',
                'sidebar_inactive' => false,
                'sidebar_order' => 7,
            ],
            [
                'sidebar_name' => 'Admin Management',
                'sidebar_inactive' => false,
                'sidebar_order' => 8,
            ],
        ];

        Sidebar::insert($sidebarData);
    }
}
