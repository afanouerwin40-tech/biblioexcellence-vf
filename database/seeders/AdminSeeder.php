<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['identifier' => 'admin'],
            [
                'name'      => 'Administrateur',
                'email'     => 'admin@biblioexcellence.edu',
                'identifier'=> 'admin',
                'password'  => Hash::make('Admin@2024!'),
                'status'    => 'approved',
                'role_type' => 'admin',
            ]
        );

        $admin->assignRole('admin');

        $this->command->info('Compte administrateur créé.');
        $this->command->info('Identifiant : admin');
        $this->command->info('Mot de passe : Admin@2024!');
    }
}