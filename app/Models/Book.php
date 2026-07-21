<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    protected $fillable = [
        'author_id',
        'category_id',
        'titre',
        'isbn',
        'editeur',
        'annee',
        'langue',
        'description',
        'couverture',
        'quantite',
        'quantite_disponible',
        'emplacement',
        'qrcode_path',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Relations
    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Vérifie si le livre est disponible
    public function isAvailable(): bool
    {
        return $this->quantite_disponible > 0;
    }
}