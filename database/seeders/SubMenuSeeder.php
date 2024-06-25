<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SubMenu;

class SubMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subMenuData = [
            [
                'sub_name' => 'Empanelled Newspaper',
                'sub_inactive' => false,
                'sub_order' => 1,
                'sub_url' => '/master-data/empanelled-newspaper',
                'sidebar_id' => 2, // Master Data sidebar ID
            ],
            [
                'sub_name' => 'Types Of Advertisements',
                'sub_inactive' => false,
                'sub_order' => 2,
                'sub_url' => '/master-data/types-of-advertisements',
                'sidebar_id' => 2, // Master Data sidebar ID
            ],
            [
                'sub_name' => 'Issue Register',
                'sub_inactive' => false,
                'sub_order' => 1,
                'sub_url' => '/reports/issue-register',
                'sidebar_id' => 5, // Reports sidebar ID
            ],
            [
                'sub_name' => 'Detailed Expenditure Report',
                'sub_inactive' => false,
                'sub_order' => 2,
                'sub_url' => '/reports/detailed-expenditure-report',
                'sidebar_id' => 5, // Reports sidebar ID
            ],
            [
                'sub_name' => 'Bills Not paid by DIPR',
                'sub_inactive' => false,
                'sub_order' => 3,
                'sub_url' => '/reports/bills-not-paid-by-dipr',
                'sidebar_id' => 5, // Reports sidebar ID
            ],
            [
                'sub_name' => 'Add',
                'sub_inactive' => false,
                'sub_order' => 1,
                'sub_url' => '/advertisements/add',
                'sidebar_id' => 4, // Advertisements sidebar ID
            ],
            [
                'sub_name' => 'Edit',
                'sub_inactive' => false,
                'sub_order' => 2,
                'sub_url' => '/advertisements/edit',
                'sidebar_id' => 4, // Advertisements sidebar ID
            ],
        ];

        SubMenu::insert($subMenuData);
    }
}
