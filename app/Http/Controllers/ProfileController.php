<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'             => ['required', 'string', 'max:150'],
            'email'            => ['required', 'email', 'unique:users,email,' . $user->id],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'current_password' => ['nullable', 'string'],
            'password'         => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required'      => 'Le nom est obligatoire.',
            'email.required'     => 'L\'email est obligatoire.',
            'email.unique'       => 'Cet email est déjà utilisé.',
            'password.min'       => 'Minimum 8 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        if ($request->hasFile('photo')) {
            $filename = 'photos/profiles/' . uniqid() . '.jpg';
            $image = Image::read($request->file('photo'))
                ->cover(400, 400)
                ->toJpeg(80);
            Storage::disk('public')->put($filename, $image);
            $user->profile_photo = $filename;
        }

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function destroy(Request $request)
    {
        return redirect('/login');
    }
}