<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminStartupMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user is a startup
        if (!$request->user() || ($request->user()->role !== 'startup' && $request->user()->role !== 'admin')) {
            return response()->json(['message' => 'Unauthorized. Only startups can access this resource.'], 403);
        }
        
        return $next($request);
    }
}
