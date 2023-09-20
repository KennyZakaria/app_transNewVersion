<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Offre;
use App\Models\Transporteur;

class Devi extends Model
{
    use HasFactory;
    protected $fillable = [
        'date',
        'prix',
        'offre_id', 
        'transporteur_id',
        'status',
        'typeVehicule',
        'dateDebut',
        'dateFin',
        'description',
        'flexibleDate'
    ];
    public function acceptAction()
    {
        return $this->hasOne(AcceptAction::class);
    }
    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
   
    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class,"transporteur_id");
    }
    public function transporteurNomPrenom()
    {
        $result = $this->belongsTo(Transporteur::class, "transporteur_id")
        ->join('users', 'users.id', '=', 'transporteurs.user_id')
        ->select(['users.firstName', 'users.lastName'])
        ->first(); // Use first() to retrieve the result

    if ($result) {
        return $result->firstName . ' ' . $result->lastName;
    } else {
        return 'Data not available'; // or return null; depending on your preference
    }
        
    }
     
}
