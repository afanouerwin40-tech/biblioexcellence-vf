<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'penalty_id', 'recu_par', 'montant_paye',
        'methode', 'reference', 'recu_pdf', 'paid_at', 'notes',
    ];

    protected $casts = [
        'paid_at'      => 'datetime',
        'montant_paye' => 'decimal:2',
    ];

    public function penalty()
    {
        return $this->belongsTo(Penalty::class);
    }
}