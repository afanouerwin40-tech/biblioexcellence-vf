<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $fillable = [
        'loan_id', 'user_id', 'jours_retard',
        'montant', 'montant_paye', 'statut', 'notes',
    ];

    protected $casts = [
        'montant'      => 'decimal:2',
        'montant_paye' => 'decimal:2',
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}