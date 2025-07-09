<?php

namespace App\Http\Middleware;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class loginAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
       
        if (!Auth::check() ) { // || !in_array(Auth::user()->user_type, $roles)
            //dd("Stop Not Authorised");
            return redirect('login')->with('error','You have not admin access');
            //abort(403); // or redirect
        }
        return $next($request);
    }
}
