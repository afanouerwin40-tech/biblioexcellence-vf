<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTeacherRequest;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class RegisterTeacherController extends Controller
{
    public function create()
    {
        $faculties   = Faculty::with('departments')->where('active', true)->get();
        $departments = Department::where('active', true)->orderBy('nom')->get();

        return view('auth.register-teacher', compact('faculties', 'departments'));
    }

    /**
     * Génère un matricule unique pour un professeur
     * Format : PRO + numéro incrémenté (ex: PRO0001)
     */
    private function generateMatricule(): string
    {
        $prefix = 'PRO';
        $last = Teacher::where('matricule_pro', 'like', $prefix . '%')
            ->orderBy('matricule_pro', 'desc')
            ->first();

        if ($last) {
            $lastNumber = (int) substr($last->matricule_pro, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    public function store(RegisterTeacherRequest $request)
    {
        DB::transaction(function () use ($request) {

            // 1. Génération automatique du matricule
            $matricule = $this->generateMatricule();

            // 2. Traitement photo
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $this->processPhoto($request->file('photo'));
            }

            // 3. Stockage carte professionnelle
            $cartePath = $request->file('carte_professionnelle')
                ->store('cartes/enseignants', 'public');

            // 4. Création compte utilisateur
            $user = User::create([
                'name'       => $request->prenom . ' ' . $request->nom,
                'email'      => $request->email,
                'identifier' => $matricule,              // Matricule généré
                'password'   => Hash::make($request->password),
                'status'     => 'pending',
                'role_type'  => 'teacher',
            ]);

            // 5. Attribution du rôle
            $user->assignRole('teacher');

            // 6. Création profil enseignant
            Teacher::create([
                'user_id'               => $user->id,
                'department_id'         => $request->department_id,
                'matricule_pro'         => $matricule,    // Matricule généré
                'nom'                   => $request->nom,
                'prenom'                => $request->prenom,
                'grade'                 => $request->grade,
                'specialite'            => $request->specialite,
                'telephone'             => $request->telephone,
                'adresse'               => $request->adresse,
                'photo'                 => $photoPath,
                'carte_professionnelle' => $cartePath,
            ]);
        });

        return redirect('/login')->with(
            'success',
            'Votre inscription a été soumise. Votre compte est en attente de validation.'
        );
    }

    private function processPhoto($file): string
    {
        $filename = 'photos/enseignants/' . uniqid() . '.jpg';

        $image = Image::read($file)
            ->cover(400, 400)
            ->toJpeg(80);

        Storage::disk('public')->put($filename, $image);

        return $filename;
    }
}
