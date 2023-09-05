<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Ville;
use App\Models\Vehicule;
use App\Models\Categorie;
//use App\Models\PropositionPrix;
use Illuminate\Support\Facades\DB;
class Transporteur extends User
{
    protected $fillable = [
        'id',
        'status',
        'CinRectoURU',
        'CinVersoURU',
        'VehicleURUS',
        'user_id',
        'ville_id',
        'pieceJoindreByType',
    ];
    public function categories()
    {
        return $this->belongsToMany(Categorie::class, 'transporteur_categorie');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function ville()
    {
        return $this->belongsTo(Ville::class);
    }
    public function vehicules()
    {
        return $this->hasMany(Vehicule::class, 'transporteur_id');
    }
    /*
    public function propositions()
    {
        return $this->hasMany(PropositionPrix::class, 'transporteur_id');
    }*/
     
    
    
}
