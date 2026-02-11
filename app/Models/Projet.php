<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'type_depot',
        'lien_depot',
        'statut',
        'score_qualite',
    ];

    protected $casts = [
        'score_qualite' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $keyType = 'int';
    public $incrementing = true;

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function analyses()
    {
        return $this->hasMany(Analyse::class);
    }

    public function derniereAnalyse()
    {
        return $this->hasOne(Analyse::class)->latest();
    }

    // Attributs personnalisés
    public function getTotalErreursAttribute()
    {
        return $this->analyses()->withCount('erreurs')->get()->sum('erreurs_count');
    }

    public function getStatutBadgeAttribute()
    {
        return match($this->statut) {
            'en_attente' => 'En attente',
            'analyse_en_cours' => 'En cours',
            'analyse_terminee' => 'Terminé',
            default => 'Inconnu',
        };
    }

    public function getScoreColorAttribute()
    {
        if ($this->score_qualite >= 80) return 'success';
        elseif ($this->score_qualite >= 60) return 'warning';
        else return 'danger';
    }

    public function getScoreFormateAttribute()
    {
        return $this->score_qualite ? round($this->score_qualite, 2) . '%' : 'N/A';
    }

    public function estAnalyse()
    {
        return $this->statut === 'analyse_terminee' && $this->analyses()->exists();
    }

    public function getIconeAttribute()
    {
        return $this->type_depot === 'GitHub' ? 'bi-github' : 'bi-file-zip';
    }
}