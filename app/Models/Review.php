<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'transporteur_id',
        'numStars',
        'comment',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function transporteur()
    {
        return $this->belongsTo(transporteur::class, 'id');
    }
}
