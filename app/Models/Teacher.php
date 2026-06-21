<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'user_id',
        'department_id',
        'matricule_pro',
        'nom',
        'prenom',
        'grade',
        'specialite',
        'telephone',
        'adresse',
        'photo',
        'carte_professionnelle',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}