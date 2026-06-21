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
     * Traite l'inscription de l'étudiant.
     */
    public function store(RegisterStudentRequest $request)
    {
        // Transaction — si une étape échoue, tout est annulé
        DB::transaction(function () use ($request) {

            // 1. Traitement de la photo de profil
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $this->processPhoto($request->file('photo'));
            }

            // 2. Stockage de la carte étudiante
            $cartePath = $request->file('carte_etudiante')
                ->store('cartes/etudiants', 'public');

            // 3. Création du compte utilisateur
            $user = User::create([
                'name'       => $request->prenom . ' ' . $request->nom,
                'email'      => $request->email,
                'identifier' => $request->matricule,
                'password'   => Hash::make($request->password),
                'status'     => 'pending',
                'role_type'  => 'student',
            ]);

            // 4. Attribution du rôle Spatie
            $user->assignRole('student');

            // 5. Création du profil étudiant
            Student::create([
                'user_id'          => $user->id,
                'department_id'    => $request->department_id,
                'matricule'        => $request->matricule,
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

        // Redirection avec message de succès
        return redirect('/login')->with(
            'success',
            'Votre inscription a été soumise avec succès. Votre compte est en attente de validation par un administrateur.'
        );
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