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
        'date_generation'
    ];

    public function analyse()
    {
        return $this->belongsTo(Analyse::class);
    }
}
