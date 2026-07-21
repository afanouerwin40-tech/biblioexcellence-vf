<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penalty extends Model
{
    protected $fillable = [
        'loan_id',
        'user_id',
        'jours_retard',
        'montant',
        'montant_paye',
        'statut',
    ];

    protected $casts = [
        'montant' => 'integer',
        'montant_paye' => 'integer',
        'jours_retard' => 'integer',
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
        return $this->hasMany(PenaltyPayment::class);
    }

    // Accesseur : montant restant à payer
    public function getResteAttribute()
    {
        return max(0, $this->montant - $this->montant_paye);
    }

    // Accesseur : statut affichable
    public function getStatusLabelAttribute()
    {
        if ($this->reste <= 0) {
            return 'Payée';
        } elseif ($this->montant_paye > 0) {
            return 'Partiellement payée';
        }
        return 'Impayée';
    }

    // Accesseur : couleur du statut
    public function getStatusColorAttribute()
    {
        if ($this->reste <= 0) {
            return 'bg-green-50 text-green-600';
        } elseif ($this->montant_paye > 0) {
            return 'bg-amber-50 text-amber-600';
        }
        return 'bg-red-50 text-red-600';
    }
}
