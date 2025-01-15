<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuarios de ejemplo
        User::create([
            'name' => 'Diego Iglesias',
            'email' => 'diego@gmail.com',
            'password' => bcrypt('password123'),
            'permiso' => false,
        ]);

        User::create([
            'name' => 'Carlos López',
            'email' => 'carlos@gmail.com',
            'password' => bcrypt('password123'),
            'permiso' => false,
        ]);

        User::create([
            'name' => 'Laura Pérez',
            'email' => 'laura@gmail.com',
            'password' => bcrypt('password123'),
            'permiso' => true,
        ]);

        // Puedes añadir más usuarios si lo necesitas
    }
}
