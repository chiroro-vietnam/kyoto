<?php

namespace Paulvl\JWTGuard\Auth\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Paulvl\JWTGuard\Auth\JWTGuard;

class AuthenticateJwt
{
    protected $inputKey = 'api_token';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'jwt', $tokenType = 'api_token')
    {
        if ($request->ajax() || $request->wantsJson()) {
            if (($errors = Auth::guard($guard)->validateToken($tokenType)) === true ) {
                if (Auth::guard($guard)->tokenIsApi()) {
                    if (Auth::guard('jwt')->guest()) {
                        return response()->json('Unauthorized.', 401);
                    }
                }
            } else {
                return response()->json($errors['message'], $errors['code']);
            }
        } else {
            return response()->json('Request must accept a json response.', 422);
        }

        return $next($request);
    }
}
