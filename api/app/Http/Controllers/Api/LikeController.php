<?php

namespace App\Http\Controllers\Api;

use App\Events\LogLikes;
use App\Models\Base;
use App\Models\Like;
use Dingo\Api\Http\Request;

/**
 * Class LikeController
 *
 * @package App\Http\Controllers\Api
 */
class LikeController extends ApiController
{
    /**
     * Like and dislike
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/likes/post-like",
     *     description="Like and dislike",
     *     operationId="api.likes.post-like",
     *     produces={"application/json"},
     *     tags={"Like Auth User"},
     *     @SWG\Parameter(
     *     name="modelId",
     *     in="query",
     *     description="ID елемента секції",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="modelType",
     *     in="query",
     *     description="news, gallery, game",
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
    public function postLikes(Request $request)
    {
        $user = $this->auth->user();
        $modelType = Base::TYPES[$request->get('modelType')];

        $likeData = [
            'model_id' => $request->get('modelId'),
            'model_type' => $modelType,
            'user_id' => $user->id
        ];

        $exists = Like::where($likeData);
        if (empty($exists->count())) {
            $like = Like::create($likeData);

            event(new LogLikes($request->get('modelId'), $modelType, $like, $user, Like::class));
        } else {
            $deleted = $exists->first();
            $deleted->delete();
        }

        $count = Like::where(['model_id' => $request->get('modelId'), 'model_type' => $modelType])->count();

        return $this->response->array(['likes' => $count]);
    }
}
