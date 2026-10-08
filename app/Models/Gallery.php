<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    // Champs de la table qu'on autorise à remplir
    protected $fillable = [
        'salon_id',
        'image_url',
        'titre',
    ];

    // Indique que cette photo appartient à un salon
    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}