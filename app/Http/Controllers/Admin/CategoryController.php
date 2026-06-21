<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('nom')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'parent_id'   => ['nullable', 'exists:categories,id'],
        ], [
            'nom.required' => 'Le nom de la catégorie est obligatoire.',
        ]);

        Category::create($request->only('nom', 'description', 'parent_id'));

        return redirect()->back()->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function destroy(Category $category)
    {
        if ($category->books()->count() > 0) {
            return redirect()->back()->withErrors([
                'category' => 'Impossible de supprimer une catégorie qui contient des livres.',
            ]);
        }

        $category->delete();
        return redirect()->back()->with('success', 'Catégorie supprimée.');
    }
}