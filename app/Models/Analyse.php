<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analyse extends Model
{
    use HasFactory;

    protected $fillable = [
        'projet_id',
        'score_qualite',
        'date_analyse',
    ];

    protected $casts = [
        'score_qualite' => 'float',
        'date_analyse' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function projet()
    {
        return $this->belongsTo(Projet::class);
    }

    public function erreurs()
    {
        return $this->hasMany(Erreur::class);
    }
}