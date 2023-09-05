<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Transporteur;
use App\Models\VehicleType; 
use App\Models\PhotoVehicule;
class Vehicule extends Model
{
    use HasFactory;
    protected $fillable = [
        'transporteur_id',
        'Marque',
        'Model', 
        'Description', 
        'vehicle_types_id',
    ];
    protected $table = 'vehicules';

    public function transporteur()
    {
        return $this->belongsTo(Transporteur::class);
    }
    public function vehiculesType()
    {
        return $this->belongsTo(Transporteur::class);
    }
    public function photos()
    {
        return $this->hasMany(PhotoVehicule::class, 'vehicule_id');
    }
}
