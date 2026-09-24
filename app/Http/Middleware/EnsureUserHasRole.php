<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Allow the request through only when the authenticated user holds one of the given roles.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(403);
        }

        $allowed = array_filter(array_map(UserRole::tryFrom(...), $roles));

        if (! in_array($user->role, $allowed, strict: true)) {
            abort(403);
        }

        return $next($request);
    }
}
