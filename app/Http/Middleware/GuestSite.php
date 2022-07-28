<?php

namespace App\Http\Middleware;


use App\Entities\UserRoles;
use Closure;

class GuestSite
{
    public function handle($request, Closure $next, $role = null)
    {
        $user = auth()->user();
        if ($user && $user->role === UserRoles::CUSTOMER && $user->isActiveUser()) {
            return redirect()->to(url('/'));
        } else if ($user && $user->role === UserRoles::CUSTOMER && $user->isNotPhoneVerified()) {
            return redirect()->to(url('/verify'));
        }
        return $next($request);
    }
}
