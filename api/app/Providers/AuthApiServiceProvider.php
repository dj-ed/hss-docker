<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Http\Request;
use Dingo\Api\Routing\Route;
use Dingo\Api\Contract\Auth\Provider;
use \Firebase\JWT\JWT;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthApiServiceProvider implements Provider
{
    public function authenticate(Request $request, Route $route)
    {
        $token = $request->headers->get('Authorization');
        if (!empty($token) && strpos($token, 'Bearer ') === 0) {
            $tokenJWT = str_replace('Bearer ', '', $token);

            try {
                $rez = JWT::decode($tokenJWT, env('APP_KEY'), array('HS256'));
                return User::where([
                    'id' => $rez->id,
                    'status' => User::STATUS_ACTIVE
                ])->firstOrFail();

            } catch (\Exception $e) {
                throw new UnauthorizedHttpException('Unable to authenticate with supplied username and password.');
            }

        }
    }
}
