<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Symfony\Component\HttpFoundation\Response;
use Helper;

class ProfileMiddleware extends BaseMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $token = JWTAuth::parseToken();
        $user = $token->authenticate();

        if (($user->role == 'COMPANY' || $user->role == 'company') && $user->company_profiles) {
            return $next($request);
        } else if (($user->role == 'USER' || $user->role == 'USER') && $user->user_profiles) {
            return $next($request);
        }

        return $this->unauthorized();
    }

	function __construct() {
	}

    private function unauthorized($message = null){
        return Helper::ErrorResponse($message ? $message : 'Please fill your profile first', Response::HTTP_UNAUTHORIZED);
    }
}
