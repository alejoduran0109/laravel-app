<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llama primero al seeder de roles y permisos
        $this->call([
            UserRolePermissionSeeder::class,
            // Otros seeders que tengas
        ]);

        // Email del usuario por defecto
        $email = 'test@example.com';

        // Verifica si el usuario ya existe para evitar duplicados
        if (!User::where('email', $email)->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => $email,
                'password' => Hash::make('password123'), // Agrega contraseña segura
            ]);
        }
    }
}
