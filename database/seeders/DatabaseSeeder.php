<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Projects;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Talha',
            'email' => 'talha@example.com',
            'password' => bcrypt('123.321@'),
            'email_verified_at' => time(),
        ]);

        Projects::factory()->count(30)->hasTasks(30)->create();
    }
}
