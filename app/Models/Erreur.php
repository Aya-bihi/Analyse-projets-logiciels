<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Erreur extends Model
{
    use HasFactory;

    protected $fillable = [
        'analyse_id',
        'categorie_id',
        'fichier',
        'ligne',
        'description',
        'gravite'
    ];

    public function analyse()
    {
        return $this->belongsTo(Analyse::class);
    }

    public function categorie()
    {
        return $this->belongsTo(ErreurCategorie::class, 'categorie_id');
    }

    public function suggestions()
    {
        return $this->hasMany(Suggestion::class);
    }
}
