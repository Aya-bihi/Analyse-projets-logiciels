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
        'gravite',
    ];

    protected $casts = [
        'ligne' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relations
    public function analyse()
    {
        return $this->belongsTo(Analyse::class);
    }

    public function categorie()
    {
        return $this->belongsTo(ErreurCategorie::class, 'categorie_id');
    }

    public function suggestion()
    {
        return $this->hasOne(Suggestion::class);
    }

    // Attributs personnalisés
    public function getGraviteColorAttribute()
    {
        return match($this->gravite) {
            'critique' => 'danger',
            'moyenne' => 'warning',
            'faible' => 'info',
            default => 'secondary',
        };
    }

    public function getNomFichierAttribute()
    {
        return basename($this->fichier);
    }

    public function getLocalisationAttribute()
    {
        $fichier = $this->nom_fichier;
        $ligne = $this->ligne ? " (ligne {$this->ligne})" : '';
        return $fichier . $ligne;
    }
}