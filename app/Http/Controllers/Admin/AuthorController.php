<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $authors = Author::withCount('books')->orderBy('nom')->paginate(20);
        return view('admin.authors.index', compact('authors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => ['required', 'string', 'max:100'],
            'prenom'      => ['nullable', 'string', 'max:100'],
            'nationalite' => ['nullable', 'string', 'max:100'],
            'biographie'  => ['nullable', 'string'],
        ], [
            'nom.required' => 'Le nom de l\'auteur est obligatoire.',
        ]);

        Author::create($request->only('nom', 'prenom', 'nationalite', 'biographie'));

        return redirect()->back()->with('success', 'Auteur ajouté avec succès.');
    }

    public function destroy(Author $author)
    {
        if ($author->books()->count() > 0) {
            return redirect()->back()->withErrors([
                'author' => 'Impossible de supprimer un auteur qui a des livres.',
            ]);
        }

        $author->delete();
        return redirect()->back()->with('success', 'Auteur supprimé.');
    }
}