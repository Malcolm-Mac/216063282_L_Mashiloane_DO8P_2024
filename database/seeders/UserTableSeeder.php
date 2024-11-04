<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' =>  'Lerato',
            'surname' =>  'Admin',
            'email' => 'leratotino@gmail.com',
            'password' => bcrypt('projectsday#2024')
        ]);

        $user->assignRole('admin');
    }
}
