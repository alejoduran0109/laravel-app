<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetear roles y permisos cacheados
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Definir Permisos con títulos
        $permissionsData = [
            // Permisos de Usuario
            ['name' => 'view_usuarios', 'title' => 'Ver Usuarios'],
            ['name' => 'create_users', 'title' => 'Crear Usuarios'],
            ['name' => 'edit_users', 'title' => 'Editar Usuarios'],
            ['name' => 'delete_users', 'title' => 'Eliminar Usuarios'],
            // Permisos de Rol
            ['name' => 'view_roles', 'title' => 'Ver Roles'],
            ['name' => 'create_roles', 'title' => 'Crear Roles'],
            ['name' => 'edit_roles', 'title' => 'Editar Roles'],
            ['name' => 'delete_roles', 'title' => 'Eliminar Roles'],
            // Permisos de Permiso (generalmente solo ver)
            ['name' => 'view_permisos', 'title' => 'Ver Permisos'],
            // General
            ['name' => 'access_admin_panel', 'title' => 'Acceder al Panel de Administración'],
        ];

        foreach ($permissionsData as $permissionItem) {
            Permission::updateOrCreate(
                ['name' => $permissionItem['name'], 'guard_name' => 'web'], // Atributos para buscar
                ['title' => $permissionItem['title']]  // Atributos para actualizar o crear
            );
        }
        $this->command->info('Permisos creados/actualizados con títulos.');

        // Definir Roles y Asignar Permisos
        // Rol Super Admin - tiene todos los permisos
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all()); // Sincroniza todos los permisos existentes
        $this->command->info('Rol Super Admin creado y todos los permisos asignados.');

        // Rol Admin - puede gestionar usuarios y roles
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $adminPermissionNames = [
            'view_usuarios', 'create_users', 'edit_users', 'delete_users',
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            'access_admin_panel', 'view_permisos',
        ];
        $adminPermissions = Permission::whereIn('name', $adminPermissionNames)->get();
        $adminRole->syncPermissions($adminPermissions);
        $this->command->info('Rol Admin creado y permisos asignados.');

        // Rol Editor - puede ver usuarios y acceder al panel de administración
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'web']);
        $editorPermissionNames = [
            'view_usuarios',
            'access_admin_panel',
        ];
        $editorPermissions = Permission::whereIn('name', $editorPermissionNames)->get();
        $editorRole->syncPermissions($editorPermissions);
        $this->command->info('Rol Editor creado y permisos asignados.');

        // Rol Usuario Básico
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        $userPermissionNames = ['access_admin_panel', 'view_permisos']; // Ejemplo: solo permitir acceso al panel
        $userPermissions = Permission::whereIn('name', $userPermissionNames)->get();
        $userRole->syncPermissions($userPermissions);
        $this->command->info('Rol User creado y permisos asignados.');

        // Crear Usuarios y Asignar Roles
        // Usuario Super Admin
        $superAdminUser = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'), // ¡Cambia esto por una contraseña segura!
            ]
        );
        $superAdminUser->assignRole($superAdminRole);
        $this->command->info('Usuario Super Admin creado y rol asignado.');

        // Usuario Admin
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'), // ¡Cambia esto por una contraseña segura!
            ]
        );
        $adminUser->assignRole($adminRole);
        $this->command->info('Usuario Admin creado y rol asignado.');

        // Usuario Editor
        $editorUser = User::firstOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor User',
                'password' => Hash::make('password123'), // ¡Cambia esto por una contraseña segura!
            ]
        );
        $editorUser->assignRole($editorRole);
        $this->command->info('Usuario Editor creado y rol asignado.');

        // Usuario Básico
        $basicUser = User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Basic User',
                'password' => Hash::make('password123'), // ¡Cambia esto por una contraseña segura!
            ]
        );
        $basicUser->assignRole($userRole); // Asignar el rol 'User'
        $this->command->info('Usuario Básico creado y rol "User" asignado.');

        $this->command->info('Seeding de Usuarios, Roles y Permisos completado exitosamente.');
    }
}
