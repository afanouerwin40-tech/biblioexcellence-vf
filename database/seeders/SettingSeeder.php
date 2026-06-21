<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['cle' => 'duree_emprunt_jours',          'valeur' => '14',                    'groupe' => 'emprunts',      'description' => 'Durée maximale d\'un emprunt en jours'],
            ['cle' => 'max_emprunts_etudiant',         'valeur' => '2',                     'groupe' => 'emprunts',      'description' => 'Maximum livres empruntés par étudiant'],
            ['cle' => 'max_emprunts_enseignant',       'valeur' => '5',                     'groupe' => 'emprunts',      'description' => 'Maximum livres empruntés par enseignant'],
            ['cle' => 'max_renouvellements',           'valeur' => '1',                     'groupe' => 'emprunts',      'description' => 'Maximum renouvellements par emprunt'],
            ['cle' => 'penalite_par_jour',             'valeur' => '100',                   'groupe' => 'penalites',     'description' => 'Pénalité par jour de retard en FCFA'],
            ['cle' => 'delai_confirmation_reservation','valeur' => '48',                    'groupe' => 'reservations',  'description' => 'Délai en heures pour confirmer une réservation'],
            ['cle' => 'nom_bibliotheque',              'valeur' => 'BiblioExcellence',      'groupe' => 'general',       'description' => 'Nom de la bibliothèque'],
            ['cle' => 'email_bibliotheque',            'valeur' => 'biblio@excellence.edu', 'groupe' => 'general',       'description' => 'Email de contact'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(['cle' => $setting['cle']], $setting);
        }

        $this->command->info('Paramètres système insérés avec succès.');
    }
}