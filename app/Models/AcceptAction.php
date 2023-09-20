<?php

namespace App\Models;
use App\Models\Devi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcceptAction extends Model
{
    use HasFactory;
    protected $fillable = [
        'devi_id',
        'prix',
        'date',
        'observations',
    ];
    public function devi()
    {
        return $this->belongsTo(Devi::class);
    }

}
