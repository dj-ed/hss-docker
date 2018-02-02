<?php

namespace App\Http\Controllers\Api;

use anlutro\cURL\Laravel\cURL;
use App\Models\User;
use App\Transformers\UserTransformer;
use Dingo\Api\Http\Request;
use \Firebase\JWT\JWT;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Api
 */
class AuthController extends ApiController
{
    /**
     * User login
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/auth/login",
     *     description="User login",
     *     operationId="api.auth.login",
     *     produces={"application/json"},
     *     tags={"Auth"},
     *     @SWG\Parameter(
     *     name="email",
     *     in="query",
     *     description="email",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="password",
     *     in="query",
     *     description="password",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function login(Request $request)
    {
        try {
            //Call Yii to check User
            $result = cURL::post(env('APP_CC_URL') . '/all-users/check-user', [
                'email' => $request->get('email'),
                'password' => $request->get('password'),
            ]);

            $resultJson = json_decode($result->body);
            if ($result->statusCode === 200 && $resultJson->success) {
                $user = User::where([
                    'id' => $resultJson->success,
                    'status' => User::STATUS_ACTIVE
                ])->first();
                if (!empty($user)) {
                    $expire = 60 * 60 * 24 * 365; // one year
                    $key = env('APP_KEY');
                    $token = array(
                        'id' => $resultJson->success,
                        "iss" => env('APP_URL'),
                        "aud" => env('APP_URL'),
                        "iat" => time(),
                        "exp" => time() + $expire
                    );
                    $token = JWT::encode($token, $key);
                    return $this->response->array(compact('token'));
                }
            }
            $this->response->error('invalid_credentials', 400);
        } catch (\ErrorException $e) {
            // something went wrong whilst attempting to encode the token
            $this->response->errorUnauthorized('invalid_encode_token');
        }
    }

    /**
     * Get user
     *
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/auth/get-user",
     *     description="Get user",
     *     operationId="api.auth.get-user",
     *     produces={"application/json"},
     *     tags={"Auth"},
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getUser()
    {

        return $this->response->item($this->auth->user(), new UserTransformer);
    }

}


