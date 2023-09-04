<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Dimension;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Article extends Model
{
    use HasFactory;
    protected $fillable = [
        'designation',
        'quantite',
    ];
    public function dimension()
    {
        return $this->hasOne(Dimension::class);
    }
}
