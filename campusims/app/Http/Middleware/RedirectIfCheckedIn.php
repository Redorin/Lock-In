<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CheckIn;
use Illuminate\Support\Facades\Auth;

class RedirectIfCheckedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->isAdmin()) {
            $activeCheckIn = CheckIn::where('user_id', Auth::id())
                ->whereNull('checked_out_at')
                ->first();

            if ($activeCheckIn) {
                // If they are checked in, they can ONLY visit these routes:
                $allowedRoutes = [
                    'student.checked-in',
                    'checkin.checkout',
                    'logout'
                ];

                if (!in_array($request->route()->getName(), $allowedRoutes)) {
                    // Redirect them to the active check-in page
                    return redirect()->route('student.checked-in');
                }
            }
        }

        return $next($request);
    }
}
