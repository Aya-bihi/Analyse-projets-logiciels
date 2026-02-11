<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErreurCategorie extends Model
{
    use HasFactory;

    protected $table = 'erreur_categories';

    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Relation avec Erreur
     * Une catégorie peut avoir plusieurs erreurs
     */
    public function erreurs()
    {
        return $this->hasMany(Erreur::class, 'categorie_id');
    }

    /**
     * Obtenir l'icône de la catégorie
     */
    public function getIconeAttribute()
    {
        return match($this->nom) {
            'Syntaxe' => 'bi-code-slash',
            'Logique' => 'bi-bug-fill',
            'Performance' => 'bi-speedometer',
            'Sécurité' => 'bi-shield-fill-exclamation',
            'Bonnes pratiques' => 'bi-check2-circle',
            default => 'bi-exclamation-circle',
        };
    }

    /**
     * Obtenir la couleur de la catégorie
     */
    public function getCouleurAttribute()
    {
        return match($this->nom) {
            'Syntaxe' => 'danger',
            'Logique' => 'warning',
            'Performance' => 'info',
            'Sécurité' => 'danger',
            'Bonnes pratiques' => 'success',
            default => 'secondary',
        };
    }
}