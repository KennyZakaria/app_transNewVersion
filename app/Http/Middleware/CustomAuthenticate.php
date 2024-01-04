<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomAuthenticate
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            return $next($request);
        }
        return redirect()->route('login');
    }
}
