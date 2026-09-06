<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UseAdminSession
{
    /**
     * Handle an incoming request.
     * Set a custom session cookie name so admin sessions are separated from user sessions.
     * This middleware must run before StartSession.
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            // This runs before StartSession, so Laravel reads the correct
            // cookie from the very first admin request (including login).
            config([
                'session.cookie' => config('session.admin_cookie'),
                'session.path' => '/admin',
            ]);
        }

        return $next($request);
    }
}
