<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Department;
use App\Models\Librarian;
use App\Models\Loan;
use App\Models\Penalty;
use App\Models\Reservation;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Jeu de données volumineux pour tester l'application dans des conditions
 * proches du réel : ~100 livres, ~700 étudiants, ~150 enseignants,
 * ~20 bibliothécaires, ~30 comptes en attente de validation, plus des
 * emprunts / réservations / pénalités pour vérifier que les notifications
 * (rappel J-3, retard, réservation disponible) se déclenchent bien.
 *
 * Prérequis : lancer d'abord `php artisan db:seed` (RoleSeeder,
 * FacultySeeder, AdminSeeder, SettingSeeder) pour avoir les rôles et
 * départements en base.
 *
 * Utilisation : php artisan db:seed --class=BigDatasetSeeder
 * Mot de passe pour tous les comptes créés : Test@1234
 */
class BigDatasetSeeder extends Seeder
{
    private array $nomsFamille = [
        'AGBEKO',
        'AMENYA',
        'ATTIVOR',
        'AYITE',
        'AZIAKOU',
        'BADJOKE',
        'BEDJA',
        'BELO',
        'BOSSA',
        'DAGNON',
        'DAMBA',
        'DANYI',
        'DEDE',
        'DEHA',
        'DOSSOU',
        'DZIFA',
        'EDEM',
        'ETSRI',
        'FOLI',
        'GABA',
        'GADO',
        'GAMELI',
        'GBEVI',
        'GNARO',
        'GNON',
        'HONOU',
        'HOTOR',
        'KOFFI',
        'KOKOU',
        'KOMLAN',
        'KPADE',
        'KPAKPO',
        'KPELI',
        'KPODO',
        'KPOGLO',
        'LARE',
        'LAWSON',
        'LOLO',
        'MAWULI',
        'MAWUTOR',
        'MENSAH',
        'MIDODJI',
        'MODZAKA',
        'NAPO',
        'NOMENYO',
        'NUVOR',
        'OLYMPIO',
        'SABI',
        'SEGLA',
        'SENU',
        'SODJI',
        'SOSSOU',
        'TCHA',
        'TCHAOU',
        'TETE',
        'TOGBE',
        'TOURE',
        'TSATSU',
        'TSEVI',
        'WOROU',
        'ABALO',
        'ADJOA',
        'AFANDE',
        'AGBOKA',
        'AKAKPO',
        'AMOUZOU',
        'ANANI',
        'ASSIH',
        'BAKO',
        'BAMAZI',
        'DJATO',
        'ESSOH',
        'GNASSOUNOU',
        'KADANGA',
        'KANFITINE',
        'LARE-DONDO',
        'NAKPANE',
        'PALI',
    ];

    private array $prenomsM = [
        'Kofi',
        'Komi',
        'Kossi',
        'Kwami',
        'Edem',
        'Foli',
        'Mawuli',
        'Komlan',
        'Gameli',
        'Selom',
        'Yao',
        'Elom',
        'Sena',
        'Delali',
        'Etse',
        'Fiifi',
        'Kekeli',
        'Nutifafa',
        'Sedem',
        'Wonder',
    ];

    private array $prenomsF = [
        'Afi',
        'Akua',
        'Ami',
        'Ama',
        'Dzifa',
        'Efua',
        'Koko',
        'Mawuko',
        'Sena',
        'Yawa',
        'Abla',
        'Adjovi',
        'Akpene',
        'Delphine',
        'Edoh',
        'Freda',
        'Kafui',
        'Mawusi',
        'Nubukpo',
        'Selorm',
    ];

    private array $editeurs = [
        'Presses Universitaires du Togo',
        'Éditions Karthala',
        'Nathan International',
        'Hachette Afrique',
        'L\'Harmattan',
        'Dunod',
        'De Boeck Supérieur',
        'CLE International',
        'EDICEF',
        'Nouvelles Éditions Africaines',
    ];

    private array $nationalitesAuteurs = ['Togolaise', 'Française', 'Béninoise', 'Ghanéenne', 'Ivoirienne'];

    private array $paragraphesDesc = [
        'Un ouvrage de référence qui couvre les notions essentielles avec clarté et rigueur, illustré de nombreux exemples pratiques.',
        'Cette édition propose une approche pédagogique progressive, adaptée aussi bien aux débutants qu\'aux étudiants avancés.',
        'Un manuel complet qui allie fondements théoriques et applications concrètes, avec des exercices corrigés en fin de chapitre.',
        'Rédigé par des spécialistes du domaine, ce livre constitue une base solide pour tout parcours universitaire dans cette discipline.',
        'Une synthèse claire et actualisée des connaissances, pensée pour accompagner les étudiants tout au long de leur cursus.',
    ];

    private function isbn13(int $seed): string
    {
        $base = str_pad((string) $seed, 9, '0', STR_PAD_LEFT);
        $digits = '978' . $base;
        $sum = 0;
        foreach (str_split($digits) as $i => $d) {
            $sum += $i % 2 === 0 ? (int) $d : (int) $d * 3;
        }
        $check = (10 - ($sum % 10)) % 10;
        return substr($digits, 0, 3) . '-' . substr($digits, 3, 1) . '-' . substr($digits, 4, 4) . '-' . substr($digits, 8, 4) . '-' . $check;
    }

    public function run(): void
    {
        $departments = Department::all();

        if ($departments->isEmpty()) {
            $this->command->error('Aucun département trouvé. Lancez d\'abord "php artisan db:seed" (RoleSeeder + FacultySeeder), puis relancez ce seeder.');
            return;
        }

        $password = Hash::make('Test@1234');

        DB::transaction(function () use ($departments, $password) {
            $livres = $this->seedBooks();
            $etudiants = $this->seedStudents($departments, $password);
            $this->seedTeachers($departments, $password);
            $this->seedLibrarians($password);
            $this->seedPendingAccounts($password);
            $this->seedLoansReservationsPenalties($etudiants, $livres);
        });

        $this->command->newLine();
        $this->command->info('==================================================');
        $this->command->info('JEU DE DONNÉES CRÉÉ — mot de passe : Test@1234');
        $this->command->info('==================================================');
        $this->command->info('Livres              : ' . Book::count());
        $this->command->info('Exemplaires          : ' . BookCopy::count());
        $this->command->info('Étudiants (approuvés): ' . Student::count());
        $this->command->info('Enseignants          : ' . Teacher::count());
        $this->command->info('Bibliothécaires      : ' . Librarian::count());
        $this->command->info('Comptes en attente   : ' . User::where('status', 'pending')->count());
        $this->command->info('Emprunts             : ' . Loan::count());
        $this->command->info('  dont en retard      : ' . Loan::where('statut', 'actif')->whereDate('date_retour_prevue', '<', now())->count() . ' (pas encore traités par loans:mark-overdue)');
        $this->command->info('  dont à échéance J+3 : ' . Loan::where('statut', 'actif')->whereDate('date_retour_prevue', now()->addDays(3))->count());
        $this->command->info('Réservations en attente : ' . Reservation::where('statut', 'en_attente')->count());
        $this->command->info('Pénalités            : ' . Penalty::count());
        $this->command->info('==================================================');
    }

    private function nom(int $i): string
    {
        return $this->nomsFamille[$i % count($this->nomsFamille)];
    }

    private function prenom(int $i, string $sexe): string
    {
        return $sexe === 'M'
            ? $this->prenomsM[$i % count($this->prenomsM)]
            : $this->prenomsF[$i % count($this->prenomsF)];
    }

    /**
     * ~100 livres avec auteurs, catégories et exemplaires.
     */
    private function seedBooks()
    {
        $this->command->info('Création des auteurs et catégories...');

        $auteurs = collect(range(1, 40))->map(function ($i) {
            $sexe = $i % 2 === 0 ? 'F' : 'M';
            return Author::firstOrCreate([
                'nom'    => $this->nom($i + 20),
                'prenom' => $this->prenom($i, $sexe),
            ], [
                'nationalite' => $this->nationalitesAuteurs[$i % count($this->nationalitesAuteurs)],
                'biographie'  => $this->paragraphesDesc[$i % count($this->paragraphesDesc)],
            ]);
        });

        $categoriesNoms = [
            'Informatique',
            'Mathématiques',
            'Droit',
            'Économie',
            'Littérature',
            'Histoire',
            'Physique',
            'Chimie',
            'Biologie',
            'Philosophie',
            'Sociologie',
            'Gestion',
            'Marketing',
            'Psychologie',
            'Médecine',
        ];
        $categories = collect($categoriesNoms)->map(
            fn($nom) => Category::firstOrCreate(['nom' => $nom])
        );

        $templates = [
            'Introduction à %s',
            'Précis de %s',
            'Manuel de %s',
            'Fondamentaux de %s',
            '%s : théorie et pratique',
            'Cours de %s',
            'Traité de %s',
            '%s appliquée',
            'Les bases de %s',
            '%s moderne',
        ];

        $this->command->info('Création de 100 livres et de leurs exemplaires...');
        $livres = collect();

        for ($i = 1; $i <= 100; $i++) {
            $cat      = $categories[$i % $categories->count()];
            $titre    = sprintf($templates[$i % count($templates)], $cat->nom) . ' — Vol. ' . (($i % 5) + 1);
            $quantite = rand(2, 6);

            $book = Book::create([
                'author_id'           => $auteurs[$i % $auteurs->count()]->id,
                'category_id'         => $cat->id,
                'titre'               => $titre,
                'isbn'                => $this->isbn13(100000000 + $i),
                'editeur'             => $this->editeurs[$i % count($this->editeurs)],
                'annee'               => rand(1998, 2025),
                'langue'              => $i % 4 === 0 ? 'Anglais' : 'Français',
                'description'         => $this->paragraphesDesc[$i % count($this->paragraphesDesc)],
                'quantite'            => $quantite,
                'quantite_disponible' => $quantite,
                'emplacement'         => 'Rayon ' . chr(65 + ($i % 10)) . '-' . rand(1, 20),
                'active'              => true,
            ]);

            for ($c = 1; $c <= $quantite; $c++) {
                BookCopy::create([
                    'book_id'         => $book->id,
                    'code_exemplaire' => 'EX-' . $book->id . '-' . str_pad((string) $c, 3, '0', STR_PAD_LEFT),
                    'etat'            => ['neuf', 'bon', 'acceptable', 'abime'][$c % 4],
                    'disponible'      => true,
                ]);
            }

            $livres->push($book);
        }

        return $livres;
    }

    /**
     * 700 étudiants approuvés, répartis sur tous les départements.
     */
    private function seedStudents($departments, string $password)
    {
        $this->command->info('Création de 700 étudiants...');
        $niveaux    = ['L1', 'L2', 'L3', 'M1', 'M2'];
        $etudiants  = collect();

        for ($i = 1; $i <= 700; $i++) {
            $sexe   = $i % 2 === 0 ? 'F' : 'M';
            $prenom = $this->prenom($i, $sexe);
            $nom    = $this->nom($i);
            $mat    = 'S' . str_pad((string) $i, 5, '0', STR_PAD_LEFT);
            $dept   = $departments->random();

            $user = User::create([
                'name'       => $prenom . ' ' . $nom,
                'email'      => strtolower($prenom . '.' . $nom . $i) . '@univ.tg',
                'identifier' => $mat,
                'password'   => $password,
                'status'     => 'approved',
                'role_type'  => 'student',
            ]);
            $user->assignRole('student');

            Student::create([
                'user_id'          => $user->id,
                'department_id'    => $dept->id,
                'matricule'        => $mat,
                'nom'              => $nom,
                'prenom'           => $prenom,
                'sexe'             => $sexe,
                'date_naissance'   => Carbon::now()->subYears(rand(18, 28))->subDays(rand(0, 365))->format('Y-m-d'),
                'nationalite'      => 'Togolaise',
                'telephone'        => '+2289' . str_pad((string) $i, 7, '0', STR_PAD_LEFT),
                'niveau'           => $niveaux[$i % count($niveaux)],
                'annee_academique' => '2025-2026',
            ]);

            $etudiants->push($user);

            if ($i % 100 === 0) {
                $this->command->info("  {$i}/700 étudiants créés...");
            }
        }

        return $etudiants;
    }

    /**
     * 150 enseignants approuvés.
     */
    private function seedTeachers($departments, string $password): void
    {
        $this->command->info('Création de 150 enseignants...');
        $grades = ['Assistant', 'Maître Assistant', 'Maître de Conférences', 'Professeur'];
        $specs  = ['Informatique', 'Mathématiques', 'Physique', 'Économie', 'Droit', 'Littérature', 'Médecine', 'Gestion'];

        for ($i = 1; $i <= 150; $i++) {
            $sexe   = $i % 2 === 0 ? 'F' : 'M';
            $prenom = $this->prenom($i + 5, $sexe);
            $nom    = $this->nom($i + 30);
            $mat    = 'T' . str_pad((string) $i, 4, '0', STR_PAD_LEFT);
            $dept   = $departments->random();

            $user = User::create([
                'name'       => $prenom . ' ' . $nom,
                'email'      => strtolower($prenom . '.' . $nom . $i) . '@faculty.tg',
                'identifier' => $mat,
                'password'   => $password,
                'status'     => 'approved',
                'role_type'  => 'teacher',
            ]);
            $user->assignRole('teacher');

            Teacher::create([
                'user_id'       => $user->id,
                'department_id' => $dept->id,
                'matricule_pro' => $mat,
                'nom'           => $nom,
                'prenom'        => $prenom,
                'grade'         => $grades[$i % count($grades)],
                'specialite'    => $specs[$i % count($specs)],
                'telephone'     => '+2289' . str_pad((string) ($i + 1000), 7, '0', STR_PAD_LEFT),
            ]);
        }
    }

    /**
     * 20 bibliothécaires.
     */
    private function seedLibrarians(string $password): void
    {
        $this->command->info('Création de 20 bibliothécaires...');

        for ($i = 1; $i <= 20; $i++) {
            $prenom = $this->prenom($i + 3, 'F');
            $nom    = $this->nom($i + 50);
            $mat    = 'L' . str_pad((string) $i, 3, '0', STR_PAD_LEFT);

            $user = User::create([
                'name'       => $prenom . ' ' . $nom,
                'email'      => strtolower($prenom . '.' . $nom . $i) . '@biblio.tg',
                'identifier' => $mat,
                'password'   => $password,
                'status'     => 'approved',
                'role_type'  => 'librarian',
            ]);
            $user->assignRole('librarian');

            Librarian::create([
                'user_id'       => $user->id,
                'matricule_pro' => $mat,
                'nom'           => $nom,
                'prenom'        => $prenom,
                'telephone'     => '+2289' . str_pad((string) ($i + 2000), 7, '0', STR_PAD_LEFT),
            ]);
        }
    }

    /**
     * 30 comptes "en attente" (sans fiche Student/Teacher, pour simuler
     * une inscription non finalisée) — parfait pour tester la page de
     * validation admin.
     */
    private function seedPendingAccounts(string $password): void
    {
        $this->command->info('Création de 30 comptes en attente de validation...');

        for ($i = 1; $i <= 30; $i++) {
            $sexe   = $i % 2 === 0 ? 'F' : 'M';
            $prenom = $this->prenom($i + 8, $sexe);
            $nom    = $this->nom($i + 60);
            $mat    = 'P' . str_pad((string) $i, 4, '0', STR_PAD_LEFT);
            $role   = $i % 5 === 0 ? 'teacher' : 'student';

            User::create([
                'name'       => $prenom . ' ' . $nom,
                'email'      => strtolower($prenom . '.' . $nom . 'pending' . $i) . '@test.tg',
                'identifier' => $mat,
                'password'   => $password,
                'status'     => 'pending',
                'role_type'  => $role,
            ]);
        }
    }

    /**
     * Emprunts, réservations et pénalités pour tester réellement le
     * système de notifications (dus dans 3 jours, retards, réservations).
     */
    private function seedLoansReservationsPenalties($etudiants, $livres): void
    {
        $this->command->info('Création des emprunts / réservations / pénalités de test...');

        $bibliothecaire = User::where('role_type', 'librarian')->first();
        $copies         = BookCopy::where('disponible', true)->inRandomOrder()->get();
        $copyIndex       = 0;

        // 1) Emprunts encore "actifs" mais déjà en retard → à traiter par
        //    `php artisan loans:mark-overdue` (pour voir la notification partir).
        for ($i = 0; $i < 25 && $copyIndex < $copies->count(); $i++, $copyIndex++) {
            $this->createLoan(
                $etudiants->random(),
                $copies[$copyIndex],
                $bibliothecaire,
                statut: 'actif',
                dateEmprunt: now()->subDays(rand(20, 40)),
                dateRetourPrevue: now()->subDays(rand(1, 10)),
            );
        }

        // 2) Emprunts actifs à échéance dans exactement 3 jours → à traiter
        //    par `php artisan notifications:due-reminders`.
        for ($i = 0; $i < 15 && $copyIndex < $copies->count(); $i++, $copyIndex++) {
            $this->createLoan(
                $etudiants->random(),
                $copies[$copyIndex],
                $bibliothecaire,
                statut: 'actif',
                dateEmprunt: now()->subDays(11),
                dateRetourPrevue: now()->addDays(3),
            );
        }

        // 3) Emprunts actifs "normaux" (échéance lointaine, rien à notifier).
        for ($i = 0; $i < 60 && $copyIndex < $copies->count(); $i++, $copyIndex++) {
            $this->createLoan(
                $etudiants->random(),
                $copies[$copyIndex],
                $bibliothecaire,
                statut: 'actif',
                dateEmprunt: now()->subDays(rand(1, 10)),
                dateRetourPrevue: now()->addDays(rand(5, 14)),
            );
        }

        // 4) Emprunts déjà retournés (avec ou sans pénalité) → historique.
        for ($i = 0; $i < 40 && $copyIndex < $copies->count(); $i++, $copyIndex++) {
            $retard = $i % 3 === 0 ? rand(1, 10) : 0;
            $loan = $this->createLoan(
                $etudiants->random(),
                $copies[$copyIndex],
                $bibliothecaire,
                statut: 'retourne',
                dateEmprunt: now()->subDays(rand(20, 60)),
                dateRetourPrevue: now()->subDays(rand(10, 20)),
                dateRetourEffective: now()->subDays(rand(5, 15)),
            );
            // Copie de nouveau disponible pour l'historique
            $loan->bookCopy->update(['disponible' => true]);

            if ($retard > 0) {
                Penalty::create([
                    'loan_id'      => $loan->id,
                    'user_id'      => $loan->user_id,
                    'jours_retard' => $retard,
                    'montant'      => $retard * 100,
                    'montant_paye' => $i % 2 === 0 ? $retard * 100 : 0,
                    'statut'       => $i % 2 === 0 ? 'payee' : 'impayee',
                ]);
            }
        }

        // 5) Réservations en attente sur des livres populaires (pour tester
        //    la file d'attente + ReservationAvailableNotification quand un
        //    exemplaire revient).
        $livresPopulaires = $livres->random(min(15, $livres->count()));
        $position = 1;
        foreach ($livresPopulaires as $index => $livre) {
            $nbReservations = rand(1, 3);
            for ($p = 1; $p <= $nbReservations; $p++) {
                Reservation::create([
                    'user_id'       => $etudiants->random()->id,
                    'book_id'       => $livre->id,
                    'position_file' => $p,
                    'statut'        => 'en_attente',
                ]);
            }
        }
    }

    private function createLoan(
        User $user,
        BookCopy $copy,
        ?User $bibliothecaire,
        string $statut,
        Carbon $dateEmprunt,
        Carbon $dateRetourPrevue,
        ?Carbon $dateRetourEffective = null,
    ): Loan {
        $copy->update(['disponible' => $statut === 'actif' ? false : true]);

        return Loan::create([
            'user_id'               => $user->id,
            'book_copy_id'          => $copy->id,
            'date_emprunt'          => $dateEmprunt,
            'date_retour_prevue'    => $dateRetourPrevue,
            'date_retour_effective' => $dateRetourEffective,
            'renouvellements'       => 0,
            'statut'                => $statut,
            'traite_par'            => $bibliothecaire?->id,
        ]);
    }
}
