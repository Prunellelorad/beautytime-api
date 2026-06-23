<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Salon;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        return response()->json([
            'total_clients'           => User::where('is_admin', false)->count(),
            'total_salons'            => Salon::count(),
            'total_reservations'      => Reservation::count(),
            'reservations_en_attente' => Reservation::where('statut', 'en_attente')->count(),
            'chiffre_affaires'        => Reservation::sum('total'),
        ]);
    }

    public function clients()
    {
        $clients = User::where('is_admin', false)
            ->withCount('reservations')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($clients);
    }

    public function deleteClient($id)
    {
        $client = User::where('id', $id)->where('is_admin', false)->first();
        if (!$client) {
            return response()->json(['message' => 'Client introuvable'], 404);
        }
        $client->delete();
        return response()->json(['message' => 'Client supprimé']);
    }

    public function salons()
    {
        return response()->json(Salon::orderBy('created_at', 'desc')->get());
    }

    public function storeSalon(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'categorie' => 'required',
            'rating'    => 'required|numeric',
            'image_url' => 'required',
        ]);

        $salon = Salon::create($request->all());
        return response()->json([
            'message' => 'Salon ajouté avec succès !',
            'salon'   => $salon,
        ], 201);
    }

    public function deleteSalon($id)
    {
        $salon = Salon::find($id);
        if (!$salon) {
            return response()->json(['message' => 'Salon introuvable'], 404);
        }
        $salon->delete();
        return response()->json(['message' => 'Salon supprimé']);
    }

    public function reservations()
    {
        $reservations = Reservation::with(['user', 'salon'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($reservations);
    }

    public function updateStatutReservation(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,confirmé,annulé',
        ]);

        $reservation = Reservation::find($id);
        if (!$reservation) {
            return response()->json(['message' => 'Réservation introuvable'], 404);
        }

        $reservation->update(['statut' => $request->statut]);
        return response()->json([
            'message'     => 'Statut mis à jour',
            'reservation' => $reservation,
        ]);
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'name'     => 'required|min:3',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $admin = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => true,
        ]);

        return response()->json([
            'message' => 'Administrateur créé avec succès !',
            'admin'   => $admin,
        ], 201);
    }
}