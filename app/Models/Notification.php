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
    public static function getUnreadCompteCreeNotifications()
    {
        return Notification::where('notificationType', 'compteCree')
            ->where('statusRead', 0)
            ->get();
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
