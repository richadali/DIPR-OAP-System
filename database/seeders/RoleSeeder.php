<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::updateOrCreate(['role_name'=>'SuperAdmin']);
        Role::updateOrCreate(['role_name'=>'Admin']);
        Role::updateOrCreate(['role_name'=>'User']);
    }
}
