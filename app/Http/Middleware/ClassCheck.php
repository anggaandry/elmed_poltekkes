<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassCheck
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
        if (active_class()) {
            return $next($request);
        } else {
            $request->session()->flush();
            Auth::guard('mahasiswa')->logout();
            return redirect('mahasiswa')->with('error_login', 'Anda tidak memiliki kelas aktif saat ini');
        }
    }
}