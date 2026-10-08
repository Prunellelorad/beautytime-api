<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'is_admin' => 'nullable|integer',
            'role'     => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $isAdminValue = (int) $request->input('is_admin', 0);

        $user = User::create([
            'name'     => $request->name,
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'is_admin' => $isAdminValue,
            'role'     => $request->input('role', $isAdminValue == 1 ? 'gerant' : 'client'),
        ]);

        // Nettoyage complet des jetons précédents et création instantanée
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'error'     => false,
            'message'   => 'Inscription réussie',
            'token'     => $token,
            'user'      => $user,
            'has_salon' => false,
            'salon'     => null,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error'   => true,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $cleanEmail = strtolower(trim($request->email));
        
        $user = User::with('salon')->where('email', $cleanEmail)->first();

        if (!$user) {
            return response()->json([
                'error'   => true,
                'message' => "Aucun compte associé à l'adresse : " . $cleanEmail,
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'error'   => true,
                'message' => "Mot de passe incorrect pour le compte " . $cleanEmail,
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'error'     => false,
            'message'   => 'Connexion réussie',
            'token'     => $token,
            'user'      => $user,
            'has_salon' => $user->salon !== null,
            'salon'     => $user->salon,
        ], 200);
    }
}