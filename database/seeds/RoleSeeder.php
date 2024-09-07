<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         // Create roles
         Role::create(['name' => 'Super Admin']);
         Role::create(['name' => 'Customer']);
         Role::create(['name' => 'Seller']);
    }
}
