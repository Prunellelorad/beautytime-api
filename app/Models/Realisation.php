<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Realisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'salon_id',
        'titre',
        'prix',
        'photo',
    ];

    public function salon()
    {
        return $this->belongsTo(Salon::class);
    }
}