<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransporteurCategorie extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'transporteur_id',  
        'categorie_id'
    ]; 
    protected $table = 'transporteur_categorie';
}
