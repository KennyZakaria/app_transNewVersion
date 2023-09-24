<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categorie;
use App\Models\Place;
use App\Models\Article;
use App\Models\Photo;
use App\Models\Devi;
use Illuminate\Database\Eloquent\SoftDeletes;
class Offre extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'dateDebut',
        'dateFin',
        'categorie',
        'status',
        'description',
        'prix',
        'placeDepart', // Field for placeDepart
        'placeArrivee', // Field for placeArrivee
        'photosUrls',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }
    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie');
    }

    public function placeDepart()
    {
        return $this->belongsTo(Place::class, 'placeDepart');
    }
    public function placeArrivee()
    {
        return $this->belongsTo(Place::class, 'placeArrivee');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class);
    }
    public function chargement()
    {
        return $this->hasOne(Chargement::class);
    }
    public function devis(): HasMany
    {
        return $this->hasMany(Devi::class);
    }
    public function getPhotosUrlsAttribute($value)
    {
        return explode(';', $value);
    }

    public function setPhotosUrlsAttribute($value)
    {
        $this->attributes['photosUrls'] = implode(';', $value);
    }

}
