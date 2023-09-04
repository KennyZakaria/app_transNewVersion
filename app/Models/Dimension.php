<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dimension extends Model
{
    use HasFactory;
    protected $fillable = [ 
        'article_id',
        'dimensionX',
        'dimensionY',
        'dimensionZ',
        'uniteDimension',
        'poid',
        'unitePoids',
    ];
}
