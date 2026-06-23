<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Non authentifié'
            ], 401);
        }

        if (!$request->user()->is_admin) {
            return response()->json([
                'message' => 'Accès refusé — Administrateur uniquement'
            ], 403);
        }

        return $next($request);
    }
}