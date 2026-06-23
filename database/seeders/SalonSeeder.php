<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Salon;

class SalonSeeder extends Seeder
{
    public function run(): void
    {
        Salon::insert([
            [
                'name'      => "L'Atelier d'Or",
                'sub_title' => 'Haute Coiffure & Spa',
                'categorie' => 'Coiffure',
                'distance'  => '0.8 km',
                'rating'    => 4.9,
                'ouverture' => '10:00',
                'fermeture' => '22:00',
                'image_url' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?q=80&w=500',
                'is_premium'=> true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => 'Maison de Beauté',
                'sub_title' => 'Facial Specialist',
                'categorie' => 'Soins',
                'distance'  => '1.2 km',
                'rating'    => 4.8,
                'ouverture' => '09:30',
                'fermeture' => '19:30',
                'image_url' => 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?q=80&w=500',
                'is_premium'=> false,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => 'The Heritage Barber',
                'sub_title' => 'Classic Grooming',
                'categorie' => 'Barbier',
                'distance'  => '2.4 km',
                'rating'    => 5.0,
                'ouverture' => '09:00',
                'fermeture' => '22:00',
                'image_url' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=500',
                'is_premium'=> true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => "L'Atelier d'Or",
                'sub_title' => 'Haute Coiffure & Spa',
                'categorie' => 'Esthetique',
                'distance'  => '0.8 km',
                'rating'    => 4.9,
                'ouverture' => '11:00',
                'fermeture' => '22:00',
                'image_url' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?q=80&w=500',
                'is_premium'=> true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => 'Maison de Beauté',
                'sub_title' => 'Facial Specialist',
                'categorie' => 'Barbier',
                'distance'  => '1.2 km',
                'rating'    => 4.8,
                'ouverture' => '08:00',
                'fermeture' => '22:00',
                'image_url' => 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?q=80&w=500',
                'is_premium'=> false,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
            [
                'name'      => 'The Heritage Barber',
                'sub_title' => 'Classic Grooming',
                'categorie' => 'Soins',
                'distance'  => '2.4 km',
                'rating'    => 5.0,
                'ouverture' => '09:00',
                'fermeture' => '20:00',
                'image_url' => 'https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=500',
                'is_premium'=> true,
                'created_at'=> now(),
                'updated_at'=> now(),
            ],
        ]);
    }
}