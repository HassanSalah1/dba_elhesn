<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Lang
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lang = $request->header('lang'); // string

        if (!empty($lang)) {
            App::setLocale($lang);
        } else {
            $lang = 'ar';
            App::setLocale($lang);
        }
        if (Auth::check()) {
            auth()->user()->update(['lang' => $lang]);
        }
        return $next($request);
    }
}
