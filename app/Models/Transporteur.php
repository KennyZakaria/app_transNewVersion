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
        'piecejoindre',
        'approuver',

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
    public function getLastNameAttribute()
    {
        $lastName = DB::table('users')->where('id', $this->id)->value('lastName');
        return $lastName;

    }
    public function getFirstNameAttribute()
    {
        $firstName = DB::table('users')->where('id', $this->id)->value('firstName');
        return $firstName;
    }
    protected $appends = ['firstName','lastName'];
    public function devis()
    {
        return $this->hasMany(Devi::class, 'transporteur_id');
    }
    /*
    public function propositions()
    {
        return $this->hasMany(PropositionPrix::class, 'transporteur_id');
    }*/



}
