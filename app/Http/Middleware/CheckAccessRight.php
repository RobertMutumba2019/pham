<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAccessRight
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $page
     * @param  string  $action
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $page, $action)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRight($page, $action)) {
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
} 