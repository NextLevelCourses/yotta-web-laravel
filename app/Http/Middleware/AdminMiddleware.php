<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import facade
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Menggunakan Auth Facade untuk kompatibilitas yang lebih baik dengan static analysis
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }
        abort(403, 'UNAUTHORIZED ACTION.');
    }
}
