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

    /**
     * Génère un matricule unique pour un bibliothécaire
     * Format : BIB + numéro incrémenté (ex: BIB0001)
     */
    private function generateMatricule(): string
    {
        $prefix = 'BIB';
        $last = Librarian::where('matricule_pro', 'like', $prefix . '%')
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

    public function store(Request $request)
    {
        $request->validate([
            'nom'           => ['required', 'string', 'max:100'],
            'prenom'        => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'telephone'     => ['nullable', 'string', 'max:20'],
            'adresse'       => ['nullable', 'string', 'max:255'],
            'password'      => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'nom.required'           => 'Le nom est obligatoire.',
            'prenom.required'        => 'Le prénom est obligatoire.',
            'email.required'         => 'L\'email est obligatoire.',
            'email.unique'           => 'Cet email est déjà utilisé.',
            'password.required'      => 'Le mot de passe est obligatoire.',
            'password.min'           => 'Minimum 8 caractères.',
            'password.confirmed'     => 'Les mots de passe ne correspondent pas.',
        ]);

        DB::transaction(function () use ($request) {
            $matricule = $this->generateMatricule();

            $user = User::create([
                'name'       => $request->prenom . ' ' . $request->nom,
                'email'      => $request->email,
                'identifier' => $matricule,
                'password'   => Hash::make($request->password),
                'status'     => 'approved',
                'role_type'  => 'librarian',
            ]);

            $user->assignRole('librarian');

            Librarian::create([
                'user_id'       => $user->id,
                'matricule_pro' => $matricule,
                'nom'           => $request->nom,
                'prenom'        => $request->prenom,
                'telephone'     => $request->telephone,
                'adresse'       => $request->adresse,
            ]);
        });

        return redirect()->route('admin.librarians.index')
            ->with('success', 'Bibliothécaire créé avec succès.');
    }

    /**
     * Affiche les détails d'un bibliothécaire.
     */
    public function show(Librarian $librarian)
    {
        $librarian->load('user');
        return view('admin.librarians.show', compact('librarian'));
    }

    public function destroy(Librarian $librarian)
    {
        $user = $librarian->user;
        $librarian->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.librarians.index')
            ->with('success', 'Bibliothécaire supprimé avec succès.');
    }
}
