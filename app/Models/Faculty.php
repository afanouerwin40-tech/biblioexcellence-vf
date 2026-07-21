<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Faculty extends Model
{
    protected $fillable = [
        'nom',
        'code',
        'description',
        'active',
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }
}