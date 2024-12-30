<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $accessType = 'admin';
        // Check if the user is logged in and has the required access type
        
        if (!Session::has('account_id')) {
            return redirect()->route('index')->with('error', 'You must be logged in to access this page.');
        }

        if ($accessType && Session::get('access_type') !== $accessType) {
            return redirect()->route('index')->with('error', 'You do not have the required access to view this page.');
        }
    
        return $next($request);
    }
}


