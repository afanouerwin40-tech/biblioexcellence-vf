<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLibrarianRequest;
use App\Models\Librarian;
use App\Models\User;
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

    public function store(StoreLibrarianRequest $request)
    {
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