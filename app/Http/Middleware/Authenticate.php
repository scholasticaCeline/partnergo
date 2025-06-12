<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- IMPORTANT: Make sure this is imported
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        // If no specific guards are provided, default to the main guard (e.g., 'web')
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            // Check if the user IS authenticated for the current guard
            if (Auth::guard($guard)->check()) {
                // If authenticated, let the request proceed to the next middleware/controller
                return $next($request);
            }
        }

        // If the loop finishes, it means the user is NOT authenticated for any of the required guards
        if (! $request->expectsJson()) {
            // For regular web requests, redirect to the login page
            return redirect()->route('login');
        }

        // For AJAX/JSON requests, you might typically throw an AuthenticationException
        // Laravel's default Authenticate middleware handles this.
        // For simplicity here, we'll let it pass (it might then result in a 401 later)
        // or you can explicitly throw:
        // throw new \Illuminate\Auth\AuthenticationException('Unauthenticated.', $guards);
        return response('Unauthorized.', 401); // Or return a JSON response
    }
}
