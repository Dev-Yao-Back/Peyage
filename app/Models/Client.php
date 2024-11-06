<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_client',
        'numero_client',
        'adresse_client',
        'telephone_client',
        'email_client',
        'note_client',

    ];
    public function operation()
    {
        return $this->belongsToMany(Operation::class,'client_operateurs');
    }

// Événement pour générer un code unique avant la création
protected static function boot()
{
    parent::boot();

    static::creating(function ($client) {
        $client->numero_client = $client->generateUniqueCode();
    });
}

// Méthode pour générer un code unique
public function generateUniqueCode()
{
    do {
        $code = strtoupper(Str::random(4)); // Code de 5 caractères aléatoires en majuscules
    } while (self::where('numero_client', $code)->exists());

    return $code;
}
}
