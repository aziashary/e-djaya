<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'hary',
            'email' => 'harriw4ng@gmail.com',
            'level' => 'Admin',
            'password' => Hash::make('qweasdzxc'),
            'username' => 'hary'
        ]);
    }
}
