<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear o verificar existencia del usuario admin
        $user = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'admin',
            'password' => bcrypt('D123d456!'),
        ]);

        // Crear o verificar existencia del rol 'admin'
        $role = Role::firstOrCreate([
            'name' => 'admin'
        ]);

        // Asignar el rol 'admin' al usuario si no lo tiene
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }
    }
}

