<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\PermissionsTableSeeder;
use Illuminate\Database\Eloquent\Model;
use Database\Seeds\RolesTableSeeder;
use Database\Seeds\ConnectRelationshipsSeeder;

class DatabaseSeeder extends Seeder
{

    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
