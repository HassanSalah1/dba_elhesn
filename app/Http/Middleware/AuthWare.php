<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthWare
{
    public function handle($request, Closure $next, $role = null)
    {

        if (!Auth::check()) {
            return redirect()->to(url('/admin/auth/login'));
        }
        return $next($request);
    }
}
