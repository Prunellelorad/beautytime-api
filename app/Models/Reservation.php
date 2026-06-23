<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model {
    protected $fillable = [
        'user_id', 'salon_id', 'date', 'heure',
        'is_at_salon', 'prix_service', 'frais_reservation', 'total', 'statut'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function salon() {
        return $this->belongsTo(Salon::class);
    }
}