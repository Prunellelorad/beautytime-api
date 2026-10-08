<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SalonController extends Controller {

    public function index(Request $request) {
        $query = Salon::query();

        if ($request->has('categorie') && $request->categorie !== 'Tout découvrir') {
            $query->where('categorie', $request->categorie);
        }

        if ($request->has('search') && $request->search !== '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    public function show($id) {
        $salon = Salon::find($id);

        if (!$salon) {
            return response()->json(['message' => 'Salon introuvable'], 404);
        }

        return response()->json($salon);
    }

    public function mySalon(Request $request) {
        $user = $request->user();

        if (!$user) {
            return response()->json(['has_salon' => false, 'message' => 'Non authentifié'], 401);
        }

        $salon = Salon::where('user_id', $user->id)->first();

        if (!$salon) {
            return response()->json(['has_salon' => false], 200);
        }

        return response()->json(['has_salon' => true, 'salon' => $salon], 200);
    }

    public function store(Request $request) {
        $name = $request->input('name') ?? $request->input('nom') ?? 'Nouveau Salon';
        $address = $request->input('address') ?? $request->input('adresse') ?? 'Calavi zogbadjè';
        $hours = $request->input('opening_hours') ?? $request->input('horaires') ?? '08h à 18h';

        $userId = $request->user()?->id ?? $request->input('user_id');

        $salon = Salon::create([
            'user_id'       => $userId,
            'title'         => $name,
            'sub_title'     => $address,
            'name'          => $name,
            'categorie'     => $request->input('categorie') ?? 'Coiffure',
            'rating'        => $request->input('rating', 5.0),
            'image_url'     => $request->input('image_url') ?? 'https://via.placeholder.com/150',
            'distance'      => $request->input('distance') ?? '1.0 km',
            'address'       => $address,
            'opening_hours' => $hours,
            'ouverture'     => $request->input('ouverture') ?? '08:00',
            'fermeture'     => $request->input('fermeture') ?? '18:00',
            'description'   => $request->input('description') ?? '',
            'latitude'      => $request->input('latitude') ?? 6.3863,
            'longitude'     => $request->input('longitude') ?? 2.3676,
        ]);

        return response()->json($salon, 201);
    }

    /**
     * Publier une nouvelle réalisation dans la galerie du salon (Gérant)
     */
    public function uploadGallery(Request $request, $salonId) {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'titre' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('galleries', 'public');

            $gallery = Gallery::create([
                'salon_id'  => $salonId,
                'image_url' => asset('storage/' . $path),
                'titre'     => $request->titre,
            ]);

            return response()->json([
                'message' => 'Réalisation ajoutée avec succès !',
                'gallery' => $gallery,
            ], 201);
        }

        return response()->json(['message' => 'Aucun fichier détecté.'], 400);
    }

    /**
     * Récupérer les photos de la galerie d'un salon (Client & Gérant)
     */
    public function getGallery($salonId) {
        $photos = Gallery::where('salon_id', $salonId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($photos, 200);
    }

    /**
     * Supprimer une photo de la galerie (Gérant)
     */
    public function deleteGalleryPhoto($id) {
        $photo = Gallery::find($id);

        if (!$photo) {
            return response()->json(['message' => 'Photo introuvable.'], 404);
        }

        $relativePath = str_replace(asset('storage/'), '', $photo->image_url);
        Storage::disk('public')->delete($relativePath);

        $photo->delete();

        return response()->json(['message' => 'Photo supprimée avec succès.'], 200);
    }
}