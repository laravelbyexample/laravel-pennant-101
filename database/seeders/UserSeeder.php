<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $factoryUsers = [
            [
                'name' => 'Admin',
                'email' => 'admin@2023.laravelconf.tw',
                'role' => 'admin'
            ],
            [
                'name' => 'Miles',
                'email' => 'miles@2023.laravelconf.tw',
                'role' => 'shipper'
            ],
            [
                'name' => 'Nathan',
                'email' => 'nathan@2023.laravelconf.tw',
                'role' => 'user'
            ],
            [
                'name' => 'Ban',
                'email' => 'ban@2023.laravelconf.tw',
                'role' => 'user'
            ],
        ];

        foreach ($factoryUsers as $user) {
            /** @var User $newUser */
            $newUser =  User::factory()->create([
                'name' => $user['name'],
                'email' => $user['email'],
            ]);

            $newUser->assignRole($user['role']);
        }
    }
}
