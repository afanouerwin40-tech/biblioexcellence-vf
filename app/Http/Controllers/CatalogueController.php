<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function show(Book $book)
    {
        // Charger les relations
        $book->load(['author', 'category', 'copies']);

        return view('catalogue.show', compact('book'));
    }
}
