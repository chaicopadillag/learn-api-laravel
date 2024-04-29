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
        User::factory(50)->create();
        User::factory()->create([
            'name'     => 'User',
            'lastname' => 'Admin',
            'email'    => 'admin@dev.com',
            'role'     => 'admin',
        ]);
        $this->call(CourseSeeder::class);

    }
}
