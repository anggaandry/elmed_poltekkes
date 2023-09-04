<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Access
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $menu, $access)
    {
        if (can($menu, $access)) {
            return $next($request);
        } else {
            if (Auth::guard('admin')->check()) {
                return redirect('4dm1n/dashboard')->with('failed', 'Anda tidak memiliki akses ke '.$access.' "'.$menu.'"');
            }
            return redirect('4dm1n')->with('error_login', 'Anda tidak memiliki akses, silahkan login terlebih dahulu');
        }
    }
}