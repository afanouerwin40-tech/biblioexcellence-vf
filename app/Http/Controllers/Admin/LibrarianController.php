<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Librarian;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LibrarianController extends Controller
{
    public function index()
    {
        $librarians = Librarian::with('user')->latest()->paginate(15);
        return view('admin.librarians.index', compact('librarians'));
    }

    public function create()
    {
        return view('admin.librarians.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'           => ['required', 'string', 'max:100'],
            'prenom'        => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'matricule_pro' => ['required', 'string', 'max:50', 'unique:librarians,matricule_pro'],
            'telephone'     => ['nullable', 'string', 'max:20'],
            'adresse'       => ['nullable', 'string', 'max:255'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nom.required'           => 'Le nom est obligatoire.',
            'prenom.required'        => 'Le prénom est obligatoire.',
            'email.required'         => 'L\'email est obligatoire.',
            'email.unique'           => 'Cet email est déjà utilisé.',
            'matricule_pro.required' => 'Le matricule est obligatoire.',
            'matricule_pro.unique'   => 'Ce matricule est déjà enregistré.',
            'password.required'      => 'Le mot de passe est obligatoire.',
            'password.min'           => 'Minimum 8 caractères.',
            'password.confirmed'     => 'Les mots de passe ne correspondent pas.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'       => $request->prenom . ' ' . $request->nom,
                'email'      => $request->email,
                'identifier' => $request->matricule_pro,
                'password'   => Hash::make($request->password),
                'status'     => 'approved',
                'role_type'  => 'librarian',
            ]);

            $user->assignRole('librarian');

            Librarian::create([
                'user_id'       => $user->id,
                'matricule_pro' => $request->matricule_pro,
                'nom'           => $request->nom,
                'prenom'        => $request->prenom,
                'telephone'     => $request->telephone,
                'adresse'       => $request->adresse,
            ]);
        });

        return redirect()->route('admin.librarians.index')
            ->with('success', 'Bibliothécaire créé avec succès.');
    }

    public function destroy(Librarian $librarian)
    {
        $librarian->user->delete();
        return redirect()->route('admin.librarians.index')
            ->with('success', 'Bibliothécaire supprimé.');
    }
}