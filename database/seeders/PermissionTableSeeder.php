<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;


class PermissionTableSeeder extends Seeder
{

public function run()
{

    $permissions = [
        'Invoices',
        'Invoices Menu',
        'Paid Invoices',
        'Permanently Paid invoices',
        'Unpaid Inovices',
        'Inovices Arvhive',
        'Reports',
        'Inovices Reports',
        'Clients Reports',
        'Users',
        'Users Menu',
        'Users Permissions',
        'Settings',
        'Products',
        'Sections',


        'Add Invoice',
        'Delete Invoice',
        'Change Payment Method',
        'Edit Invoice',
        'Archive Invoice',
        'Print Inovice',
        'Add Attachment',
        'Delete Attachment',

        'Add User',
        'Edit User',
        'Delete User',

        'Show Access',
        'Add Access',
        'Edit Access',
        'Delete Access',

        'Add Product',
        'Edit product',
        'Delete Product',

        'Add Section',
        'Edit Section',
        'Delete Section',
        'Notifications',
    ];

    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission]);
    }
}
}