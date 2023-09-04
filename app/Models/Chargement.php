<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chargement extends Model
{
    use HasFactory;
    protected $fillable = [
        'chargement',
        'dechargement',
        'etageChargement',
        'etageDechargement',
        'ascenceurChargement',
        'ascenceurDechargement',
    ];
}
