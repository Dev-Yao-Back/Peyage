<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transporteur extends Model
{
    use HasFactory;

    protected $fillable = ['nom_transporteur', 'numero_transporteur', 'telephone_transporteur',  'email_transporeur', 'adresse_transporteur', 'type_transporteur'];

    public function operation()
    {
        return $this->hasMany(Operation::class);
    }
}