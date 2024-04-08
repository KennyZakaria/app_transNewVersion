<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static function findByPlaceId($placeId)
    {
        return self::where('placeId', $placeId)->first();
    }

}
