<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

       User::create([
            'name' => 'Diego Iglesias',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678')
        ]);

        $this->call([
            UserSeeder::class,          // Crea usuarios
            CuposPorHoraSeeder::class,  // Crea cupos por hora
            CitasSeeder::class,         // Crea citas
        ]);
    }
}
