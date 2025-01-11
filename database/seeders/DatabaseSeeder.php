<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Test Admin',
            'email' => 'testadmin@example.com',
            'password' => Hash::make('password'),
            'phone' => '123456789',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Test Tutor',
            'email' => 'testtutor@example.com',
            'password' => Hash::make('password'),
            'phone' => '123456789',
            'role' => 'tutor',
        ]);

        User::create([
            'name' => 'Test Student',
            'email' => 'teststudent@example.com',
            'password' => Hash::make('password'),
            'phone' => '123456789',
            'role' => 'student',
        ]);
        

        $this->call([
            CategorySeeder::class,
        ]);
    }
}
