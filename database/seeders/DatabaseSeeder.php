<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Business;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        Role::create(['name' => 'admin', 'guard_name' => 'web']);
        Role::create(['name' => 'vendedor', 'guard_name' => 'web']);

        // Crear usuario admin de prueba
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('admin');

        // Crear un negocio de prueba
        Business::create([
            'user_id' => $user->id,
            'name' => 'Mi Tienda',
            'industry' => 'Retail',
            'currency' => 'USD',
            'is_active' => true,
        ]);

        // Qué puede hacer cada rol
        Permission::create(['name' => 'gestionar productos']);
        Permission::create(['name' => 'registrar ventas']);
        Permission::create(['name' => 'ver reportes']);
        Permission::create(['name' => 'gestionar equipo']);

        $admin->givePermissionTo(Permission::all());
        $vendedor->givePermissionTo(['registrar ventas']);
    }
}
