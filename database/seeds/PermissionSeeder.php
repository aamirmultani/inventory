<?php

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Customer permissions
        Permission::create(['name' => 'create customer']);
        Permission::create(['name' => 'view customer']);
        Permission::create(['name' => 'update customer']);
        Permission::create(['name' => 'delete customer']);

        // Seller permissions
        Permission::create(['name' => 'create seller']);
        Permission::create(['name' => 'view seller']);
        Permission::create(['name' => 'edit seller']);
        Permission::create(['name' => 'delete seller']);

        // Product permissions
        Permission::create(['name' => 'create product']);
        Permission::create(['name' => 'view product']);
        Permission::create(['name' => 'edit product']);
        Permission::create(['name' => 'delete product']);

        // Category permissions
        Permission::create(['name' => 'create category']);
        Permission::create(['name' => 'view category']);
        Permission::create(['name' => 'edit category']);
        Permission::create(['name' => 'delete category']);

        // Orders permissions
        Permission::create(['name' => 'create order']);
        Permission::create(['name' => 'view order']);
        Permission::create(['name' => 'edit order']);
        Permission::create(['name' => 'delete order']);
    }
}
