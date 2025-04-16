<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminFormateurMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->role !== 'admin' && $request->user()->role !== 'formateur') {
            return redirect()->route('dashboard')->with('error', 'Vous n avez pas d accès administrateur');
        }
        // If the user is an admin or formateur, allow the request to proceed
        return $next($request);
    }
}
