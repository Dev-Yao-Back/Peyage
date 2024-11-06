<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campagne extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'date_debut',
        'date_fin',
        'reduction',
        'entreprise_id',

    ];

    public function entreprise()
    {
        return $this->BelongsTo(Entreprise::class);
    }
}
