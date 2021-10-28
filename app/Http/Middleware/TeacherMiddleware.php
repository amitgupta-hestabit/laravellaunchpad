<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherMiddleware
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
        if (Auth::check() && (Auth::user()->user_type == 'ADMIN' || Auth::user()->user_type == 'TEACHER')) {
            return $next($request);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
    }
}
