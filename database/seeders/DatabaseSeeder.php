<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Post;
use App\Models\Message;
use App\Models\UserTeaching;

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

        Post::create([
            'user_id' => 2, // Make sure you have a valid user_id
            'subject_id' => 1, // Make sure you have a valid subject_id
            'title' => 'Tutor Post 1',
            'content' => 'This is a post created by a tutor.',
        ]);

        Post::create([
            'user_id' => 3,
            'subject_id' => 2, // Make sure you have a valid subject_id
            'title' => 'Student Post 1',
            'content' => 'This is a post created by a student.',
        ]);

        UserTeaching::create([
            'user_id' => 2,
            'subject_id' => 1,
            'rate' => 50,
        ]);

        UserTeaching::create([
            'user_id' => 2,
            'subject_id' => 2,
            'rate' => 60,
        ]);

        Message::create([
            'sender_id' => 2,
            'receiver_id' => 3,
            'content' => 'Hello, I am available for tutoring sessions.',
        ]);

        Message::create([
            'sender_id' => 3,
            'receiver_id' => 2,
            'content' => 'Great! I would like to schedule a session.',
        ]);
    }
}
