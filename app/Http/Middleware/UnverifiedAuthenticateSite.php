<?php

namespace App\Http\Middleware;


use App\Entities\Status;
use App\Entities\UserRoles;
use Closure;

class UnverifiedAuthenticateSite
{
    public function handle($request, Closure $next, $role = null)
    {
        $user = auth()->user();

        if ($user && $user->status === Status::UNVERIFIED) {
            return $next($request);
        }
        return redirect()->to(url('/'));
    }
}
