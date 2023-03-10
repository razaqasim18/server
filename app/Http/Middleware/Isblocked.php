<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Isblocked
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->user()->is_block) {
            if (
                Auth::getDefaultDriver() == 'admin' &&
                Auth::guard('admin')->check()
            ) {
                Auth::guard($guard)->logout();
                return redirect()
                    ->route('admin.adminLogin')
                    ->with('error', 'Your account is not blocked');
            } else {
                return redirect()
                    ->route('login')
                    ->with('error', 'Your account is not blocked');
            }
        }
        return $next($request);
    }
}
