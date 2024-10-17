<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Color;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Type;
use App\Models\User;
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

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'number_registration' => 111111,
            'is_admin' => 'admin',
            'password' => Hash::make('password')
        ]);

        Type::factory(5)->create();

        Color::factory(15)->create();

        Client::factory(125)->create();
    }
}
