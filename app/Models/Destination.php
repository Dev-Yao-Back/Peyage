<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = ['nom_destination', 'adresse_destination', 'region_destination', 'ville_destination','numero_destination'];

    public function operations()
    {
        return $this->hasMany(Operation::class);
    }
}
