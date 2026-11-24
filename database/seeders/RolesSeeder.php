<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define roles
        $roles = [
            'Student',
            'University',
            'Admin',
        ];

        // Create roles if they don’t exist
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Define permissions (optional, extend as needed)
        $permissions = [
            'apply for program',
            'upload documents',
            'review applications',
            'manage universities',
            'manage users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions (example)
        $studentRole = Role::where('name', 'student')->first();
        $studentRole->givePermissionTo(['apply for program', 'upload documents']);

        $universityRole = Role::where('name', 'university')->first();
        $universityRole->givePermissionTo(['review applications', 'manage universities']);

        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->givePermissionTo(Permission::all());
    }
}
