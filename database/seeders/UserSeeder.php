<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            'name' => 'asiya nurhasanah habirun',
            'email' => 'asiyanurhasanah@gmail.com',
            'password' => bcrypt('Asiya1234'),
        ]);
    }
}
