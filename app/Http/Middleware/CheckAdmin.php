<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckAdmin
{
    public function handle($request, Closure $next)
    {
        Log::info('CheckAdmin middleware invoked.');
        if (Auth::check() && Auth::user()->is_admin) {
            Log::info('Admin access granted.');
            return $next($request);
        }

        Log::warning('Admin access denied.');
        return redirect('/')->with('error', 'You do not have admin access.');
    }
}