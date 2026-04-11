<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureRejected
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->isRejected()) {
            return redirect()->route('student.dashboard');
        }
        return $next($request);
    }
}
