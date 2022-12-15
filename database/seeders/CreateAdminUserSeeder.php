<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateAdminUserSeeder extends Seeder
{


public function run()
{
    $user = User::create([
        'name' => 'amr emam', 
        'email' => 'erorat10@gmail.com',
        'password' => bcrypt('123456'),
        'roles_name' => ["owner"],
        'Status' => 'activate',
        ]);

        $role = Role::create(['name' => 'owner']);
   
        $permissions = Permission::pluck('id','id')->all();
  
        $role->syncPermissions($permissions);
   
        $user->assignRole([$role->id]);

}
}