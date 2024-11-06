<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = [

        'code',
        'numero_vehcule',
        'poids1',
        'poids2',
        'montant_paye',
        'datepoids1',
        'datepoids2',
        'heurepoids1',
        'heurepoids2',
        'poidsnet',
        'type_operation',
        'transporteur_id',
        'fournisseur_id',
        'produit_id',
        'provenance_id',
        'destination_id',
         'statut',
        'peseur_id',



    ];

    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function provenance()
    {
        return $this->belongsTo(Provenance::class);
    }

    public function peseur()
    {
        return $this->belongsTo(peseur::class);
    }

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }



    public function client_operateur()
    {
        return $this->hasMany(ClientOperateur::class);
    }
}
