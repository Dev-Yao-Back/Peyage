<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    use HasFactory;

    protected $fillable = ['nom_produit', 'description_produit', 'prix_unitaire','prix_unitaire_revente' , 'unite_id'];

    public function operation()
    {
        return $this->hasMany(Operation::class);
    }
    public function unite()
    {
        return $this->belongsTo(Unite::class);
    }
}
