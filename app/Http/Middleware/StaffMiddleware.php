<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isStaff()) {
            return $next($request);
        }

        return redirect('/login')->withErrors('You do not have staff access.');
    }
}
