<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
        'analyse_id',
        'chemin_pdf',
        'date_generation',
    ];

    protected $casts = [
        'date_generation' => 'datetime',
    ];

    /**
     * Relation avec Analyse
     * Un rapport appartient à une analyse
     */
    public function analyse()
    {
        return $this->belongsTo(Analyse::class);
    }

    /**
     * Obtenir l'URL de téléchargement du PDF
     */
    public function getUrlTelechargementAttribute()
    {
        return route('rapports.download', $this->id);
    }

    /**
     * Vérifier si le fichier PDF existe
     */
    public function pdfExiste()
    {
        return file_exists(storage_path('app/' . $this->chemin_pdf));
    }
}