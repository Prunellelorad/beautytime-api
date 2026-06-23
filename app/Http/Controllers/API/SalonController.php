<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use Illuminate\Http\Request;

class SalonController extends Controller {

    public function index(Request $request) {
        $query = Salon::query();

        // Filtre par catégorie
        if ($request->has('categorie') && $request->categorie !== 'Tout découvrir') {
            $query->where('categorie', $request->categorie);
        }

        // Recherche par nom
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

    public function store(Request $request) {
        $request->validate([
            'name'      => 'required',
            'categorie' => 'required',
            'rating'    => 'required|numeric',
            'image_url' => 'required',
        ]);

        $salon = Salon::create($request->all());
        return response()->json($salon, 201);
    }
}