<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BillType;

class BillTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BillType::updateOrCreate(['bill_type_name'=>'Paid by DIPR']);
        BillType::updateOrCreate(['bill_type_name'=>'Not paid by DIPR']);
    }
}
