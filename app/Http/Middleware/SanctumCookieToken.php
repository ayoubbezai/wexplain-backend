<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class SanctumCookieToken
{
public function handle(Request $request, Closure $next)
{
    if (!$request->bearerToken()) {
        if ($request->cookie('auth_token')) {
            $token = $request->cookie('auth_token');
            $request->headers->set('Authorization', 'Bearer '.$token);
        } else {
            // No cookie, manually return JSON
            return response()->json([
                'success' => false,
                'message' => 'not_logged_in',
                'user' => null
            ], 401);
        }
    }

    return $next($request);
}

}
