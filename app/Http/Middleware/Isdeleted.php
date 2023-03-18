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
                Auth::guard('admin')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
               
                return redirect()
                    ->route('admin.adminLogin')
                    ->with('error', 'Your account is not deleted');
            } 
            
            if(
                Auth::getDefaultDriver() == 'web' &&
                Auth::guard('web')->check()
            ) {
                Auth::guard('web')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()
                    ->route('login')
                    ->with('error', 'Your account is not blocked');
            }
        }
        return $next($request);
    }
}
