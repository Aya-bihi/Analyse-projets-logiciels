<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErreurCategorie extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'description'];

    public function erreurs()
    {
        return $this->hasMany(Erreur::class, 'categorie_id');
    }
}
