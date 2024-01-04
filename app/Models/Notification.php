<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'notificationType',
        'dateCreation',
        'statusRead',
        'deviDemandeCompteId',
        'notificationContent'
    ];

    protected $casts = [
        'dateCreation' => 'datetime',
        'statusRead' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
