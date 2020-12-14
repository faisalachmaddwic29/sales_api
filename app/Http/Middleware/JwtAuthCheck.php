<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Helper;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthCheck
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
        $helper = new Helper();
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return $helper->response_api_error('Kami tidak memiliki catatan tengtang akun Anda. Silahkan melakukan pendaftaran', 'user_not_found');
            }

            if (!$user->status) {
                return $helper->response_api_error('Akun Anda belom diaktifkan oleh Admin', 'user_not_active');
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return $helper->response_api_error($e->getMessage(), 'token_expired');
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return $helper->response_api_error($e->getMessage(), 'token_invalid');
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return $helper->response_api_error($e->getMessage(), 'token_required');
        } catch (\Exception $e) {
            return $helper->response_api_server_error($e->getMessage());
        }
        return $next($request);
    }
}
