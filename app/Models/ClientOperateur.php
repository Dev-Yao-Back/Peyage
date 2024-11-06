<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientOperateur extends Model
{
    use HasFactory;
     protected $fillable = [
        'client_id',
        'operation_id'

     ];
     public function operations()
     {
         return $this->belongsTo(Operation::class);
     }

     public function client()
     {
         return $this->belongsTo(Client::class);
     }

}