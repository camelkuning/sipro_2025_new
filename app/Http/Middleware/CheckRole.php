<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle(Request $request, Closure $next, $role)
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Harap login terlebih dahulu');
        }

        // Cek apakah role user sesuai
        if (Auth::user()->role !== $role) {
            abort(403, 'Anda tidak memiliki akses');
        }

        return $next($request);
    }
}
