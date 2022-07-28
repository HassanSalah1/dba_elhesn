<?php

namespace App\Http\Middleware;


use App\Entities\Status;
use App\Entities\UserRoles;
use Closure;

class AuthenticateSite
{
    public function handle($request, Closure $next, $role = null)
    {
        $user = auth()->user();

        if (!$user || $user->role !== UserRoles::CUSTOMER
            || $user->status === Status::INACTIVE) {
            return redirect()->to(url('/login'));
        } else if ($user && $user->status === Status::UNVERIFIED) {
            return redirect()->to(url('/verify'));
        }
        return $next($request);
    }
}
