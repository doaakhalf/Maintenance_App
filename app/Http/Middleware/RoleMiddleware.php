<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,...$roles)
    {
          // Ensure the user is authenticated
          if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        // Get the user's role
        $user = Auth::user();

        // Check if the user's role is allowed
       
        if (!in_array($user->role->role_name, $roles)) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
