<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class peseur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'numero',
        'note',
        'adresse',
        'telephone',
        'email'
    ];
}
