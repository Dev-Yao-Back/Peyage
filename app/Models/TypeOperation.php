<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOperation extends Model
{
    use HasFactory;

    protected $table = 'type_operations';

    // Attributs qui peuvent être assignés en masse
    protected $fillable = ['nom'];

    /**
     * Relation avec le modèle Operation (Une TypeOperation peut avoir plusieurs Operations)
     */
    public function operation()
    {
        return $this->hasMany(Operation::class, 'type_operation_id');
    }
}
