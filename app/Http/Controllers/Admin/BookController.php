<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Services\ImageService;
use App\Services\QrCodeService;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    public function __construct(
        private ImageService  $imageService,
        private QrCodeService $qrCodeService
    ) {}

    public function index()
    {
        $books = Book::with(['author', 'category'])
            ->latest()
            ->paginate(20);

        $stats = [
            'total'       => Book::count(),
            'disponibles' => Book::where('quantite_disponible', '>', 0)->count(),
            'epuises'     => Book::where('quantite_disponible', 0)->count(),
        ];

        return view('admin.books.index', compact('books', 'stats'));
    }

    public function create()
    {
        $authors    = Author::orderBy('nom')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('admin.books.create', compact('authors', 'categories'));
    }

    public function store(StoreBookRequest $request)
    {
        DB::transaction(function () use ($request) {

            // 1. Traitement couverture
            $couverturePath = null;
            if ($request->hasFile('couverture')) {
                $couverturePath = $this->imageService->processCover($request->file('couverture'));
            }

            // 2. Création du livre
            $book = Book::create([
                'author_id'           => $request->author_id,
                'category_id'         => $request->category_id,
                'titre'               => $request->titre,
                'isbn'                => $request->isbn,
                'editeur'             => $request->editeur,
                'annee'               => $request->annee,
                'langue'              => $request->langue,
                'description'         => $request->description,
                'couverture'          => $couverturePath,
                'quantite'            => $request->quantite,
                'quantite_disponible' => $request->quantite,
                'emplacement'         => $request->emplacement,
            ]);

            // 3. Génération QR Code
            $qrcodePath = $this->qrCodeService->generateForBook($book->id);
            $book->update(['qrcode_path' => $qrcodePath]);

            // 4. Création des exemplaires
            for ($i = 1; $i <= $request->quantite; $i++) {
                BookCopy::create([
                    'book_id'          => $book->id,
                    'code_exemplaire'  => 'EX-' . $book->id . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'etat'             => 'neuf',
                    'disponible'       => true,
                ]);
            }
        });

        return redirect()->route('admin.books.index')
            ->with('success', 'Livre ajouté avec succès.');
    }

    public function show(Book $book)
    {
        $book->load(['author', 'category', 'copies']);

        return view('admin.books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        $authors    = Author::orderBy('nom')->get();
        $categories = Category::whereNull('parent_id')->with('children')->get();

        return view('admin.books.edit', compact('book', 'authors', 'categories'));
    }

    public function update(StoreBookRequest $request, Book $book)
    {
        // Traitement couverture
        if ($request->hasFile('couverture')) {
            $this->imageService->delete($book->couverture);
            $book->couverture = $this->imageService->processCover($request->file('couverture'));
        }

        $book->update([
            'author_id'   => $request->author_id,
            'category_id' => $request->category_id,
            'titre'       => $request->titre,
            'isbn'        => $request->isbn,
            'editeur'     => $request->editeur,
            'annee'       => $request->annee,
            'langue'      => $request->langue,
            'description' => $request->description,
            'couverture'  => $book->couverture,
            'emplacement' => $request->emplacement,
        ]);

        return redirect()->route('admin.books.index')
            ->with('success', 'Livre modifié avec succès.');
    }

    public function destroy(Book $book)
    {
        $this->imageService->delete($book->couverture);
        $this->imageService->delete($book->qrcode_path);
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Livre supprimé avec succès.');
    }
}