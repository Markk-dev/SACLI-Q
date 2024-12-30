<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
public function run(): void
{
    User::factory()->create([
        'name' => 'Admin',
        'account_id' => '0000-0001',
        'access_type' => 'admin',
        'password' => bcrypt('Password'),
    ]);
}
}
