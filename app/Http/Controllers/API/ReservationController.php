<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Salon;
use Illuminate\Http\Request;

class ReservationController extends Controller {

    /**
     * Récupère la liste des réservations de l'utilisateur connecté (Client).
     */
    public function index(Request $request) {
        $reservations = Reservation::with('salon')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservations);
    }

    /**
     * Enregistre une nouvelle réservation en bloquant les créneaux déjà pris.
     */
    public function store(Request $request) {
        $request->validate([
            'salon_id'     => 'required|exists:salons,id',
            'date'         => 'required|string',
            'heure'        => 'required|string',
            'is_at_salon'  => 'required|boolean',
            'prix_service' => 'nullable|numeric',
        ]);

        // Vérification : le créneau est-il déjà occupé par une réservation active ?
        $existe = Reservation::where('salon_id', $request->salon_id)
            ->where('date', $request->date)
            ->where('heure', $request->heure)
            ->whereIn('statut', ['en_attente', 'confirmee', 'accepte'])
            ->exists();

        if ($existe) {
            return response()->json([
                'message' => 'Ce créneau est déjà pris pour ce salon. Veuillez choisir une autre heure.',
            ], 409);
        }

        $prixService = $request->prix_service ?? 0;
        $frais = 0; 
        $total = $prixService + $frais; 

        $reservation = Reservation::create([
            'user_id'           => $request->user()->id,
            'salon_id'          => $request->salon_id,
            'date'              => $request->date,
            'heure'             => $request->heure,
            'is_at_salon'       => $request->is_at_salon,
            'prix_service'      => $prixService,
            'frais_reservation' => $frais,
            'total'             => $total,
            'statut'            => 'en_attente',
        ]);

        return response()->json([
            'message'     => 'Réservation créée avec succès !',
            'reservation' => $reservation->load('salon'),
        ], 201);
    }

    /**
     * DASHBOARD GÉRANT : Récupère les réservations du salon du gérant connecté.
     */
    public function managerIndex(Request $request) {
        $user = $request->user();

        $reservations = Reservation::with(['user', 'salon'])
            ->whereHas('salon', function ($query) use ($user) {
                $query->where('gerant_id', $user->id)
                      ->orWhere('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservations);
    }

    /**
     * DASHBOARD : Récupère les réservations pour un salon spécifique via ID.
     */
    public function salonReservations(Request $request, $salonId) {
        $reservations = Reservation::with('user')
            ->where('salon_id', $salonId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservations);
    }

    /**
     * DASHBOARD GÉRANT : Met à jour le statut d'une réservation.
     */
    public function updateStatus(Request $request, $id) {
        $request->validate([
            'statut' => 'required|in:confirmee,annulee,terminee,refusee,en_attente,accepte,refuse,termine',
        ]);
        $reservation = Reservation::find($id);

        if (!$reservation) {
            return response()->json(['message' => 'Réservation introuvable'], 404);
        }

        $reservation->statut = $request->statut;
        $reservation->save();

        return response()->json([
            'message' => 'Statut mis à jour avec succès',
            'reservation' => $reservation
        ]);
    }

    /**
     * Annule une réservation existante.
     */
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

    /**
     * Récupère les réservations du client connecté.
     */

    public function getUserReservations(Request $request)
{
    // 🟢 FORCE L'ID 4 TEMPORAIREMENT POUR TESTER :
    $reservations = Reservation::where('user_id', 4)->get();

    return response()->json($reservations);
}
//    public function getUserReservations(Request $request) {
//     $user = $request->user();

//     if (!$user) {
//         return response()->json([]);
//     }

//     // Requête directe ultra-rapide sans jointure lourde
//     $reservations = Reservation::where('user_id', $user->id)
//         ->orderBy('id', 'desc')
//         ->get();

//     return response()->json($reservations);
// }

}