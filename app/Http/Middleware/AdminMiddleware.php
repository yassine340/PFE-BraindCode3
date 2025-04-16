<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
{
    if (Auth::check()) {
        if (Auth::user()->role === 'admin') {
            return $next($request);
        } elseif (Auth::user()->role === 'formateur') {
            return redirect()->route('DashboardFormateur')->with('error', 'Vous n\'avez pas d\'accès administrateur');
        } else {
            return redirect()->route('dashboard')->with('error', 'Vous n\'avez pas d\'accès administrateur');
        }
    }
    
    // Handle unauthenticated users
    return redirect()->route('login');
}
}