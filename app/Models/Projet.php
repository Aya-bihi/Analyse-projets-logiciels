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
        'score_qualite'
    ];

    public function analyses()
    {
        return $this->hasMany(Analyse::class);
    }
}
