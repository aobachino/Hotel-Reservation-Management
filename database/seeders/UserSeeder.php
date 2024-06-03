<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // User::factory(5)->create();
        User::create([
            'name' => 'Wailan Tirajoh',
            'email' => 'wailantirajoh@gmail.com',
            'password' => Hash::make('wailan'),
            'role' => 'Super',
            'random_key' => Str::random(60)
        ]);

        User::create([
            'name' => 'Aoba Chino',
            'email' => 'chino@graduate.utm.my',
            'password' => Hash::make('aoba'),
            'role' => 'Admin',
            'random_key' => Str::random(60)
        ]);

        User::create([
            'name' => 'Tong',
            'email' => 'aaaa@aaaa.utm.my',
            'password' => Hash::make('tong'),
            'role' => 'Admin',
            'random_key' => Str::random(60)
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'role' => 'Customer',
            'password' => Hash::make('john'),
            'random_key' => Str::random(60), // You may generate random keys if needed
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'role' => 'Customer',
            'password' => Hash::make('jane'),
            'random_key' => Str::random(60), // You may generate random keys if needed
        ]);

        User::create([
            'name' => 'Alice Johnson',
            'email' => 'alice@example.com',
            'role' => 'Customer',
            'password' => Hash::make('alice'),
            'random_key' => Str::random(60), // You may generate random keys if needed
        ]);

    }
}
