<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // Validation commune
        $rules = [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        // Règles spécifiques selon le rôle
        $role = $user->role_type;
        if ($role === 'student') {
            $rules['telephone'] = ['nullable', 'string', 'max:20'];
            $rules['adresse'] = ['nullable', 'string', 'max:255'];
            $rules['niveau'] = ['nullable', 'string', 'max:50'];
            $rules['annee_academique'] = ['nullable', 'string', 'max:20'];
        } elseif ($role === 'teacher') {
            $rules['telephone'] = ['nullable', 'string', 'max:20'];
            $rules['adresse'] = ['nullable', 'string', 'max:255'];
            $rules['grade'] = ['nullable', 'string', 'max:100'];
            $rules['specialite'] = ['nullable', 'string', 'max:255'];
        }

        // Validation du mot de passe si présent
        if ($request->filled('current_password') || $request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password'];
            $rules['password'] = ['required', 'string', 'min:8', 'confirmed'];
        }

        $request->validate($rules);

        // Mise à jour du modèle User
        $user->name = $request->name;
        $user->email = $request->email;

        // Photo de profil
        if ($request->hasFile('photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('photo')->store('photos/profiles', 'public');
            $user->profile_photo = $path;
        }

        // Mot de passe
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Mise à jour du profil spécifique
        if ($role === 'student') {
            $student = $user->student;
            if ($student) {
                $student->telephone = $request->telephone;
                $student->adresse = $request->adresse;
                $student->niveau = $request->niveau;
                $student->annee_academique = $request->annee_academique;
                $student->save();
            } else {
                // Créer le profil étudiant s'il n'existe pas (cas rare)
                $user->student()->create([
                    'telephone' => $request->telephone,
                    'adresse'   => $request->adresse,
                    'niveau'    => $request->niveau,
                    'annee_academique' => $request->annee_academique,
                ]);
            }
        } elseif ($role === 'teacher') {
            $teacher = $user->teacher;
            if ($teacher) {
                $teacher->telephone = $request->telephone;
                $teacher->adresse = $request->adresse;
                $teacher->grade = $request->grade;
                $teacher->specialite = $request->specialite;
                $teacher->save();
            } else {
                $user->teacher()->create([
                    'telephone' => $request->telephone,
                    'adresse'   => $request->adresse,
                    'grade'     => $request->grade,
                    'specialite' => $request->specialite,
                ]);
            }
        }

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function destroy(Request $request)
    {
        // Option de suppression de compte (à implémenter selon vos besoins)
        return redirect('/login');
    }
}
