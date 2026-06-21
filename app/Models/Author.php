<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'biographie',
        'nationalite',
    ];

    public function books()
    {
        return $this->hasMany(Book::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}