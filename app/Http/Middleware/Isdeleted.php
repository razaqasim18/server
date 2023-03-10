<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class Isdeleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->user()->is_deleted) {
            if (
                Auth::getDefaultDriver() == 'admin' &&
                Auth::guard('admin')->check()
            ) {
                Auth::guard($guard)->logout();
                return redirect()
                    ->route('admin.adminLogin')
                    ->with('error', 'Your account is not deleted');
            } else {
                return redirect()
                    ->route('login')
                    ->with('error', 'Your account is not deleted');
            }
        }
        return $next($request);
    }
}
