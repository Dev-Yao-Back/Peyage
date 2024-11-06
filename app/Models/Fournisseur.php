<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_fournisseur',
        'numero_fournisseur',
        'adresse_fournisseur',
        'telephone_fournisseur',
        'email_fournisseur',
        'capacite_production_mensuelle',
    ];

    public function operation()
    {
        return $this->hasMany(Operation::class);
    }
}
