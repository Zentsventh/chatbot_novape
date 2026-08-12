<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAccess
{
    /**
     * Inyecta tenant_id del usuario autenticado en el request
     * y bloquea acceso cross-tenant.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if (!$user->tenant_id) {
            return response()->json(['error' => 'User has no tenant assigned'], 403);
        }

        // Inyectar tenant_id en el request para uso global
        $request->merge(['tenant_id' => $user->tenant_id]);

        return $next($request);
    }
}
