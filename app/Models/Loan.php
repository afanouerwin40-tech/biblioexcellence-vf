<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id', 'book_copy_id', 'date_emprunt',
        'date_retour_prevue', 'date_retour_effective',
        'renouvellements', 'statut', 'traite_par', 'notes',
    ];

    protected $casts = [
        'date_emprunt'          => 'date',
        'date_retour_prevue'    => 'date',
        'date_retour_effective' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class);
    }

    public function penalty()
    {
        return $this->hasOne(Penalty::class);
    }
}