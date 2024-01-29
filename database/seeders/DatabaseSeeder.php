<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'test',
            'email' => 'test@example.com',
            'password' => Hash::make('zxczxczxc')
        ]);

        \App\Models\Role::create([
            'title' => 'administrator'
        ]);

        \App\Models\Role::create([
            'title' => 'user'
        ]);

        \App\Models\Storage::create([
            'title' => 'Sample Title',
            'location' => 'Sample Location',
            'details' => 'Sample Details'
        ]);

        \App\Models\Category::create([
            'title' => 'Sample Title',
            'details' => 'Sample Details'
        ]);

        User::where('email', 'test@example.com')->first()->roles()->attach(Role::where('title', 'administrator')->first()->id);

        // \App\Models\UserRole::create([
        //     'role_id' => \App\Models\Role::where('title', 'administrator')->first()->id,
        //     'user_id' => \App\Models\User::where('email', 'test@example.com')->first()->id
        // ]);
    }
}
