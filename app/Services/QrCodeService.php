<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeService
{
    /**
     * Génère un QR code pour un livre et le stocke.
     * Le QR code contient l'URL de la fiche du livre.
     */
    public function generateForBook(int $bookId): string
    {
        // URL encodée dans le QR code
        $url = config('app.url') . '/books/' . $bookId;

        // Nom du fichier
        $filename = 'qrcodes/books/book_' . $bookId . '.svg';

        // Génération du QR code en SVG
        $qrcode = QrCode::format('svg')
            ->size(300)
            ->errorCorrection('H')
            ->generate($url);

        // Stockage
        Storage::disk('public')->put($filename, $qrcode);

        return $filename;
    }
}