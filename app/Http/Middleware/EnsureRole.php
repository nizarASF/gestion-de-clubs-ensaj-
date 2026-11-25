<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/** Middleware de zone : `role:bde` ou `role:club_member`. */
class EnsureRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = Auth::user();
        if (! $user || ! in_array($user->role->value, $roles, true)) {
            abort(403, "Vous n'avez pas accès à cette zone.");
        }

        return $next($request);
    }
}
