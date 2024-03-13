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

        \App\Models\User::factory()->create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'password' => Hash::make('zxczxczxc')
        ]);

        \App\Models\Role::create([
            'title' => 'administrator'
        ]);

        \App\Models\Role::create([
            'title' => 'user'
        ]);

        \App\Models\Storage::create([
            'title' => 'Sample Storage Title',
            'location' => 'Sample Storage Location',
            'details' => 'Sample Storage Details'
        ]);

        \App\Models\Category::create([
            'title' => 'Sample Category Title 1',
            'type' => '1',
            'details' => 'Sample Category Details 1'
        ]);

        \App\Models\Category::create([
            'title' => 'Resolutions',
            'type' => '1',
            'details' => 'Sample Resolution Details'
        ]);

        \App\Models\Category::create([
            'title' => 'Ordinances',
            'type' => '1',
            'details' => 'Sample Ordinance Details'
        ]);

        \App\Models\Category::create([
            'title' => 'Sample Category Title 2',
            'type' => '2',
            'details' => 'Sample Category Details 2'
        ]);

        User::where('email', 'test@example.com')->first()->roles()->attach(Role::where('title', 'administrator')->first()->id);
        User::where('email', 'test1@example.com')->first()->roles()->attach(Role::where('title', 'administrator')->first()->id);


        \App\Models\User::factory()->count(100)->hasAttached(\App\Models\Role::where('title', 'user')->first())->create();
    }
}
