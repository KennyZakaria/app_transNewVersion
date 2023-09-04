<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class Client extends User
{
    protected $fillable = [
        'id', 
        'user_id',
        'tel',
    ];
    use HasFactory;
}
