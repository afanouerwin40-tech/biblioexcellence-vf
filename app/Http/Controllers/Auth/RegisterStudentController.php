<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterStudentRequest;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class RegisterStudentController extends Controller
{
    /**
     * Affiche le formulaire d'inscription étudiant.
     */
    public function create()
    {
        $faculties   = Faculty::with('departments')->where('active', true)->get();
        $departments = Department::where('active', true)->orderBy('nom')->get();

        return view('auth.register-student', compact('faculties', 'departments'));
    }

    /**
     * Génère un matricule unique pour un étudiant
     * Format : ETU + numéro incrémenté (ex: ETU0001)
     */
    private function generateMatricule(): string
    {
        $prefix = 'ETU';
        $last = Student::where('matricule', 'like', $prefix . '%')
            ->orderBy('matricule', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->matricule, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Traite l'inscription de l'étudiant.
     */
    public function store(RegisterStudentRequest $request)
    {
        $matricule = null;

        // Transaction — si une étape échoue, tout est annulé
        DB::transaction(function () use ($request, &$matricule) {

            // 1. Génération automatique du matricule
            $matricule = $this->generateMatricule();

            // 2. Traitement de la photo de profil
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $this->processPhoto($request->file('photo'));
            }

            // 3. Stockage de la carte étudiante
            $cartePath = $request->file('carte_etudiante')
                ->store('cartes/etudiants', 'public');

            // 4. Création du compte utilisateur
            $user = User::create([
                'name'       => $request->prenom . ' ' . $request->nom,
                'email'      => $request->email,
                'identifier' => $matricule,              // Matricule généré
                'password'   => Hash::make($request->password),
                'status'     => 'pending',
                'role_type'  => 'student',
            ]);

            // 5. Attribution du rôle Spatie
            $user->assignRole('student');

            // 6. Création du profil étudiant
            Student::create([
                'user_id'          => $user->id,
                'department_id'    => $request->department_id,
                'matricule'        => $matricule,        // Matricule généré
                'nom'              => $request->nom,
                'prenom'           => $request->prenom,
                'sexe'             => $request->sexe,
                'date_naissance'   => $request->date_naissance,
                'nationalite'      => $request->nationalite,
                'telephone'        => $request->telephone,
                'adresse'          => $request->adresse,
                'niveau'           => $request->niveau,
                'annee_academique' => $request->annee_academique,
                'photo'            => $photoPath,
                'carte_etudiante'  => $cartePath,
            ]);
        });

        // Redirection vers la page d'attente de validation
        return redirect()->route('pending.approval')->with([
            'registered_name'      => trim($request->prenom . ' ' . $request->nom),
            'registered_email'     => $request->email,
            'registered_matricule' => $matricule,
            'registered_role'      => 'Étudiant',
        ]);
    }

    /**
     * Compresse et redimensionne la photo de profil.
     */
    private function processPhoto($file): string
    {
        // Génère un nom unique
        $filename = 'photos/etudiants/' . uniqid() . '.jpg';

        // Redimensionne à 400x400 et compresse
        $image = Image::read($file)
            ->cover(400, 400)
            ->toJpeg(80);

        // Stocke dans storage/app/public
        Storage::disk('public')->put($filename, $image);

        return $filename;
    }
}
