<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Librarian extends Model
{
    protected $fillable = [
        'user_id',
        'matricule_pro',
        'nom',
        'prenom',
        'telephone',
        'adresse',
        'photo',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}