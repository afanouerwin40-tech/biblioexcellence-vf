<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Librarian;
use App\Models\Department;

class TestUsersSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        if ($departments->isEmpty()) {
            $this->command->error('Aucun département trouvé. Lancez FacultySeeder d\'abord.');
            return;
        }

        $password = Hash::make('Test@1234');

        // ================================================================
        // COMPTES DE TEST PRINCIPAUX (accès facile)
        // ================================================================

        // Étudiant test principal
        $etudiant1 = User::firstOrCreate(
            ['identifier' => 'ET0001'],
            [
                'name'      => 'KOUASSI Amavi',
                'email'     => 'etudiant@test.com',
                'identifier'=> 'ET0001',
                'password'  => $password,
                'status'    => 'approved',
                'role_type' => 'student',
            ]
        );
        $etudiant1->assignRole('student');
        Student::firstOrCreate(['user_id' => $etudiant1->id], [
            'user_id'          => $etudiant1->id,
            'department_id'    => $departments->first()->id,
            'matricule'        => 'ET0001',
            'nom'              => 'KOUASSI',
            'prenom'           => 'Amavi',
            'sexe'             => 'M',
            'date_naissance'   => '2000-05-15',
            'nationalite'      => 'Togolaise',
            'telephone'        => '+22890000001',
            'niveau'           => 'L3',
            'annee_academique' => '2025-2026',
        ]);

        // Enseignant test principal
        $prof1 = User::firstOrCreate(
            ['identifier' => 'ENS0001'],
            [
                'name'      => 'AGBODJAN Kossi',
                'email'     => 'enseignant@test.com',
                'identifier'=> 'ENS0001',
                'password'  => $password,
                'status'    => 'approved',
                'role_type' => 'teacher',
            ]
        );
        $prof1->assignRole('teacher');
        Teacher::firstOrCreate(['user_id' => $prof1->id], [
            'user_id'       => $prof1->id,
            'department_id' => $departments->first()->id,
            'matricule_pro' => 'ENS0001',
            'nom'           => 'AGBODJAN',
            'prenom'        => 'Kossi',
            'grade'         => 'Maître Assistant',
            'specialite'    => 'Informatique',
            'telephone'     => '+22890000002',
        ]);

        // Bibliothécaire test principal
        $bibli1 = User::firstOrCreate(
            ['identifier' => 'BIB0001'],
            [
                'name'      => 'MENSAH Afi',
                'email'     => 'bibliothecaire@test.com',
                'identifier'=> 'BIB0001',
                'password'  => $password,
                'status'    => 'approved',
                'role_type' => 'librarian',
            ]
        );
        $bibli1->assignRole('librarian');
        Librarian::firstOrCreate(['user_id' => $bibli1->id], [
            'user_id'       => $bibli1->id,
            'matricule_pro' => 'BIB0001',
            'nom'           => 'MENSAH',
            'prenom'        => 'Afi',
            'telephone'     => '+22890000003',
        ]);

        $this->command->info('Comptes principaux créés.');

        // ================================================================
        // 60 ÉTUDIANTS
        // ================================================================
        $noms = ['AGBEKO','AMENYA','ATTIVOR','AYITE','AZIAKOU','BADJOKE',
                 'BEDJA','BELO','BOSSA','DAGNON','DAMBA','DANYI','DEDE',
                 'DEHA','DOSSOU','DZIFA','EDEM','ETSRI','FOLI','GABA',
                 'GADO','GAMELI','GBEVI','GNARO','GNON','HONOU','HOTOR',
                 'KOFFI','KOKOU','KOMLAN','KPADE','KPAKPO','KPELI','KPODO',
                 'KPOGLO','LARE','LAWSON','LOLO','MAWULI','MAWUTOR',
                 'MENSAH','MIDODJI','MODZAKA','NAPO','NOMENYO','NUVOR',
                 'OLYMPIO','SABI','SEGLA','SENU','SODJI','SOSSOU',
                 'TCHA','TCHAOU','TETE','TOGBE','TOURE','TSATSU','TSEVI','WOROU'];

        $prenoms_m = ['Kofi','Komi','Kossi','Kwami','Edem','Foli','Mawuli','Komlan','Gameli','Selom'];
        $prenoms_f = ['Afi','Akua','Ami','Ama','Dzifa','Efua','Koko','Mawuko','Sena','Yawa'];
        $niveaux   = ['L1','L2','L3','M1','M2'];
        $sexes     = ['M','F'];

        for ($i = 1; $i <= 60; $i++) {
            $sexe   = $sexes[$i % 2];
            $prenom = $sexe === 'M'
                ? $prenoms_m[$i % count($prenoms_m)]
                : $prenoms_f[$i % count($prenoms_f)];
            $nom    = $noms[$i % count($noms)];
            $mat    = 'ET' . str_pad($i + 100, 4, '0', STR_PAD_LEFT);
            $dept   = $departments->random();

            $user = User::firstOrCreate(
                ['identifier' => $mat],
                [
                    'name'      => $prenom . ' ' . $nom,
                    'email'     => strtolower($prenom . '.' . $nom . $i) . '@univ.tg',
                    'identifier'=> $mat,
                    'password'  => $password,
                    'status'    => 'approved',
                    'role_type' => 'student',
                ]
            );
            $user->assignRole('student');

            Student::firstOrCreate(['user_id' => $user->id], [
                'user_id'          => $user->id,
                'department_id'    => $dept->id,
                'matricule'        => $mat,
                'nom'              => $nom,
                'prenom'           => $prenom,
                'sexe'             => $sexe,
                'date_naissance'   => '200' . ($i % 5) . '-0' . ($i % 9 + 1) . '-15',
                'nationalite'      => 'Togolaise',
                'telephone'        => '+2289' . str_pad($i, 7, '0', STR_PAD_LEFT),
                'niveau'           => $niveaux[$i % count($niveaux)],
                'annee_academique' => '2025-2026',
            ]);
        }

        $this->command->info('60 étudiants créés.');

        // ================================================================
        // 25 ENSEIGNANTS
        // ================================================================
        $grades = ['Assistant','Maître Assistant','Maître de Conférences','Professeur'];
        $specs  = ['Informatique','Mathématiques','Physique','Économie','Droit','Littérature'];

        for ($i = 1; $i <= 25; $i++) {
            $sexe   = $i % 2 === 0 ? 'F' : 'M';
            $prenom = $sexe === 'M'
                ? $prenoms_m[$i % count($prenoms_m)]
                : $prenoms_f[$i % count($prenoms_f)];
            $nom    = $noms[($i + 30) % count($noms)];
            $mat    = 'ENS' . str_pad($i + 100, 4, '0', STR_PAD_LEFT);
            $dept   = $departments->random();

            $user = User::firstOrCreate(
                ['identifier' => $mat],
                [
                    'name'      => $prenom . ' ' . $nom,
                    'email'     => strtolower($prenom . '.' . $nom . $i) . '@faculty.tg',
                    'identifier'=> $mat,
                    'password'  => $password,
                    'status'    => 'approved',
                    'role_type' => 'teacher',
                ]
            );
            $user->assignRole('teacher');

            Teacher::firstOrCreate(['user_id' => $user->id], [
                'user_id'       => $user->id,
                'department_id' => $dept->id,
                'matricule_pro' => $mat,
                'nom'           => $nom,
                'prenom'        => $prenom,
                'grade'         => $grades[$i % count($grades)],
                'specialite'    => $specs[$i % count($specs)],
                'telephone'     => '+2289' . str_pad($i + 1000, 7, '0', STR_PAD_LEFT),
            ]);
        }

        $this->command->info('25 enseignants créés.');

        // ================================================================
        // 5 BIBLIOTHÉCAIRES SUPPLÉMENTAIRES
        // ================================================================
        for ($i = 2; $i <= 5; $i++) {
            $prenom = $prenoms_f[$i % count($prenoms_f)];
            $nom    = $noms[($i + 50) % count($noms)];
            $mat    = 'BIB' . str_pad($i, 4, '0', STR_PAD_LEFT);

            $user = User::firstOrCreate(
                ['identifier' => $mat],
                [
                    'name'      => $prenom . ' ' . $nom,
                    'email'     => strtolower($prenom . '.' . $nom . $i) . '@biblio.tg',
                    'identifier'=> $mat,
                    'password'  => $password,
                    'status'    => 'approved',
                    'role_type' => 'librarian',
                ]
            );
            $user->assignRole('librarian');

            Librarian::firstOrCreate(['user_id' => $user->id], [
                'user_id'       => $user->id,
                'matricule_pro' => $mat,
                'nom'           => $nom,
                'prenom'        => $prenom,
                'telephone'     => '+2289' . str_pad($i + 2000, 7, '0', STR_PAD_LEFT),
            ]);
        }

        $this->command->info('5 bibliothécaires supplémentaires créés.');

        // ================================================================
        // 10 COMPTES EN ATTENTE (pour tester la validation)
        // ================================================================
        for ($i = 1; $i <= 10; $i++) {
            $mat  = 'PEND' . str_pad($i, 4, '0', STR_PAD_LEFT);
            $nom  = $noms[($i + 10) % count($noms)];
            $prenom = $prenoms_m[$i % count($prenoms_m)];

            $user = User::firstOrCreate(
                ['identifier' => $mat],
                [
                    'name'      => $prenom . ' ' . $nom,
                    'email'     => strtolower($prenom . '.' . $nom . 'pending' . $i) . '@test.tg',
                    'identifier'=> $mat,
                    'password'  => $password,
                    'status'    => 'pending',
                    'role_type' => 'student',
                ]
            );
        }

        $this->command->info('10 comptes en attente créés.');
        $this->command->newLine();
        $this->command->info('==============================================');
        $this->command->info('COMPTES DE TEST PRINCIPAUX');
        $this->command->info('==============================================');
        $this->command->info('MOT DE PASSE UNIVERSEL : Test@1234');
        $this->command->newLine();
        $this->command->info('ADMIN        → identifiant: admin');
        $this->command->info('ÉTUDIANT     → identifiant: ET0001  | email: etudiant@test.com');
        $this->command->info('ENSEIGNANT   → identifiant: ENS0001 | email: enseignant@test.com');
        $this->command->info('BIBLIOTHÉC.  → identifiant: BIB0001 | email: bibliothecaire@test.com');
        $this->command->info('==============================================');
    }
}