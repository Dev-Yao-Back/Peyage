<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entreprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'logo',

        'adresse',
        'ville',
        'region',
        'telephone',
        'email',

        'site_web',
        'date_creation',
        'capacite',
        'horaires_ouverture',
        'responsable_gestion',
        'types_paiement_acceptes',
        'entretien_maintenance',
        'statut_juridique',

    ];

    public function campagne()
    {
        return $this->hasMany(Campagne::class);
    }

}