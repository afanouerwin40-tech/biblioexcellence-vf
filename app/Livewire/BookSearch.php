<?php

namespace App\Livewire;

use App\Models\Book;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class BookSearch extends Component
{
    use WithPagination;

    public string $search     = '';
    public string $category   = '';
    public string $langue      = '';
    public string $disponible  = '';

    protected $queryString = [
        'search'    => ['except' => ''],
        'category'  => ['except' => ''],
        'langue'    => ['except' => ''],
        'disponible'=> ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingCategory(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Book::with(['author', 'category'])
            ->where('active', true);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('titre', 'like', '%' . $this->search . '%')
                  ->orWhere('isbn', 'like', '%' . $this->search . '%')
                  ->orWhereHas('author', function ($q2) {
                      $q2->where('nom', 'like', '%' . $this->search . '%')
                         ->orWhere('prenom', 'like', '%' . $this->search . '%');
                  });
            });
        }

        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        if ($this->langue) {
            $query->where('langue', $this->langue);
        }

        if ($this->disponible === '1') {
            $query->where('quantite_disponible', '>', 0);
        } elseif ($this->disponible === '0') {
            $query->where('quantite_disponible', 0);
        }

        $books      = $query->latest()->paginate(12);
        $categories = Category::orderBy('nom')->get();
        $langues    = Book::distinct()->pluck('langue')->sort();

        return view('livewire.book-search', compact('books', 'categories', 'langues'));
    }
}