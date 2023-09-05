<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Vehicule;
class PhotoVehicule extends Model
{
    use HasFactory;
    protected $fillable = [
            'url',
        ];
    public function vehicule()
    {
        
        return $this->belongsTo(Vehicule::class);
    }
}
