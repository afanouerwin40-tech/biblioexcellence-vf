<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('book.author')
            ->where('user_id', auth()->id())
            ->whereIn('statut', ['en_attente', 'disponible'])
            ->orderBy('created_at')
            ->get();

        return view('student.reservations', compact('reservations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'book_id' => ['required', 'exists:books,id'],
        ]);

        $user = auth()->user();
        $book = Book::findOrFail($request->book_id);

        // Vérifier si déjà réservé
        $exists = Reservation::where('user_id', $user->id)
            ->where('book_id', $book->id)
            ->whereIn('statut', ['en_attente', 'disponible'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Vous avez déjà une réservation active pour ce livre.');
        }

        // Vérifier si le livre est disponible
        if ($book->isAvailable()) {
            return back()->with('error', 'Ce livre est disponible. Empruntez-le directement à la bibliothèque.');
        }

        // Position dans la file
        $position = Reservation::where('book_id', $book->id)
            ->where('statut', 'en_attente')
            ->count() + 1;

        Reservation::create([
            'user_id'       => $user->id,
            'book_id'       => $book->id,
            'position_file' => $position,
            'statut'        => 'en_attente',
        ]);

        return back()->with('success', "Réservation enregistrée. Vous êtes en position {$position} dans la file d'attente.");
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        $reservation->update(['statut' => 'annulee']);

        // Réorganiser la file
        $this->reorderQueue($reservation->book_id);

        return back()->with('success', 'Réservation annulée.');
    }

    private function reorderQueue(int $bookId): void
    {
        $reservations = Reservation::where('book_id', $bookId)
            ->where('statut', 'en_attente')
            ->orderBy('created_at')
            ->get();

        foreach ($reservations as $index => $res) {
            $res->update(['position_file' => $index + 1]);
        }
    }
}
