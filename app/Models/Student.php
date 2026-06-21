<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'matricule',
        'nom',
        'prenom',
        'sexe',
        'date_naissance',
        'nationalite',
        'telephone',
        'adresse',
        'niveau',
        'annee_academique',
        'photo',
        'carte_etudiante',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}