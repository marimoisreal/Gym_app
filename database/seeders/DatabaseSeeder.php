<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // From the start we seed roles first
        $this->call(RoleSeeder::class);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@gym.com',
            'password' => bcrypt('password'),
            'role_id' => 1, // Assuming 1 is the ID for Admin role
            'slug' => 'admin'
        ]);

        User::create([
            'name' => 'Ivan Ivanov',
            'email' => 'ivan@gym.com',
            'password' => bcrypt('password'),
            'role_id' => 2, // 2 - Member
            'slug' => 'ivan-ivanov'
        ]);

        \App\Models\Subscription::create([
            'user_id' => 2, 
            'start_date' => now(),
            'end_date' => now()->addMonth(), // 1 month subscription
            'type' => 'monthly',
            'price' => 50.00,
        ]);
    }
}
