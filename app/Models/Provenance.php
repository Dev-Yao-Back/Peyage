<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provenance extends Model
{
    use HasFactory;

    protected $fillable = ['nom_provenance', 'numero_provenance', 'adresse_provenance', 'region_provenance', 'ville_provenance'];

    public function operation()
    {
        return $this->hasMany(Operation::class);
    }
}
