<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if( $request->user()->usertype_id == 1){
                    return redirect(RouteServiceProvider::ADMIN_HOME);
                }elseif( $request->user()->usertype_id == 9){
                    return redirect(RouteServiceProvider::TREASURY_HOME);
                }else{
                    return redirect(RouteServiceProvider::ACCOUNTING_HOME);
                }

            }
        }

        return $next($request);
    }
}
