<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'sub_title',
        'name',
        'categorie',
        'rating',
        'image_url',
        'distance',
        'address',
        'opening_hours',
        'ouverture',
        'fermeture',
        'description',
        'latitude',
        'longitude',
        
    ];
}