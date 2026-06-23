<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model {
    protected $fillable = [
        'name', 'sub_title', 'categorie', 'distance',
        'rating', 'ouverture', 'fermeture', 'image_url', 'is_premium'
    ];

    public function reservations() {
        return $this->hasMany(Reservation::class);
    }
}