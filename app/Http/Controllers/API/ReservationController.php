<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller {

    public function index(Request $request) {
        $reservations = Reservation::with('salon')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservations);
    }

    public function store(Request $request) {
        $request->validate([
            'salon_id'     => 'required|exists:salons,id',
            'date'         => 'required|string',
            'heure'        => 'required|string',
            'is_at_salon'  => 'required|boolean',
            'prix_service' => 'required|numeric',
        ]);

        // ✅ ANTI-DOUBLON : même salon, même date, même heure
        $existe = Reservation::where('salon_id', $request->salon_id)
            ->where('date', $request->date)
            ->where('heure', $request->heure)
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Ce créneau est déjà pris pour ce salon. Veuillez choisir une autre heure.',
            ], 409);
        }

        $frais      = 1000;
        $deplacement = $request->is_at_salon ? 0 : 2000;
        $total      = $request->prix_service + $frais + $deplacement;

        $reservation = Reservation::create([
            'user_id'           => $request->user()->id,
            'salon_id'          => $request->salon_id,
            'date'              => $request->date,
            'heure'             => $request->heure,
            'is_at_salon'       => $request->is_at_salon,
            'prix_service'      => $request->prix_service,
            'frais_reservation' => $frais,
            'total'             => $total,
            'statut'            => 'en_attente',
        ]);

        return response()->json([
            'message'     => 'Réservation créée avec succès !',
            'reservation' => $reservation->load('salon'),
        ], 201);
    }

    public function destroy(Request $request, $id) {
        $reservation = Reservation::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (!$reservation) {
            return response()->json(['message' => 'Réservation introuvable'], 404);
        }

        $reservation->delete();
        return response()->json(['message' => 'Réservation annulée']);
    }
}