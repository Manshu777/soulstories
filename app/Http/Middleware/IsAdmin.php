<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('admin') ?? $request->user();

        if (! $user || ! $user->hasAnyRole(['Super Admin', 'Admin', 'Moderator', 'Editor', 'admin'])) {
            abort(403, 'Admin access required.');
        }

        return $next($request);
    }
}
