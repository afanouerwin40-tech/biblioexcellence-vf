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

    public function store(RegisterTeacherRequest $request)
    {
        DB::transaction(function () use ($request) {

            // 1. Traitement photo
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $this->processPhoto($request->file('photo'));
            }

            // 2. Stockage carte professionnelle
            $cartePath = $request->file('carte_professionnelle')
                ->store('cartes/enseignants', 'public');

            // 3. Création compte utilisateur
            $user = User::create([
                'name'       => $request->prenom . ' ' . $request->nom,
                'email'      => $request->email,
                'identifier' => $request->matricule_pro,
                'password'   => Hash::make($request->password),
                'status'     => 'pending',
                'role_type'  => 'teacher',
            ]);

            // 4. Attribution du rôle
            $user->assignRole('teacher');

            // 5. Création profil enseignant
            Teacher::create([
                'user_id'               => $user->id,
                'department_id'         => $request->department_id,
                'matricule_pro'         => $request->matricule_pro,
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