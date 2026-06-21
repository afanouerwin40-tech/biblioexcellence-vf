<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (['admin','librarian','teacher','student'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $permissions = [
            'view users','create users','edit users','delete users','approve users','suspend users',
            'view books','create books','edit books','delete books',
            'view loans','create loans','edit loans','delete loans',
            'process returns','view penalties','create penalties','edit penalties','process payments',
            'view reservations','create reservations','cancel reservations',
            'view reports','export reports','view settings','edit settings',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::findByName('admin')->syncPermissions(Permission::all());
        Role::findByName('librarian')->syncPermissions([
            'view users','view books','create books','edit books',
            'view loans','create loans','edit loans','process returns',
            'view penalties','create penalties','process payments',
            'view reservations','view reports','export reports',
        ]);
        Role::findByName('teacher')->syncPermissions([
            'view books','view loans','create reservations','cancel reservations',
            'view penalties','view reservations',
        ]);
        Role::findByName('student')->syncPermissions([
            'view books','view loans','create reservations','cancel reservations',
            'view penalties','view reservations',
        ]);

        $this->command->info('Rôles et permissions créés avec succès.');
    }
}