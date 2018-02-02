<?php

namespace App\Http\Controllers\Api;

use Abraham\TwitterOAuth\TwitterOAuth;
use Dingo\Api\Http\Request;

/**
 * Class HomeController
 *
 * @package App\Http\Controllers\Api
 */
class HomeController extends ApiController
{
    public function index()
    {
        return $this->response->array([1, 2, 3]);
    }

    public function twitter(Request $request){
        $connection = new TwitterOAuth(env('TW_CONSUMER_KEY'), env('TW_CONSUMER_SECRET'), env('TW_ACCESS_TOKEN'), env('TW_ACCESS_SECRET'));
        $rez=$connection->get('statuses/user_timeline',['screen_name'=>$request->get('page'),'count'=>$request->get('count')]);

        return $rez;
    }
}