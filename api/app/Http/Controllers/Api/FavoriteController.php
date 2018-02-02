<?php

namespace App\Http\Controllers\Api;

use App\Models\Base;
use App\Models\Favorite;
use Dingo\Api\Http\Request;

/**
 * Class FavoriteController
 *
 * @package App\Http\Controllers\Api
 */
class FavoriteController extends ApiController
{
    /**
     * Favorite and unfavorite
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/favorites/post-favorite",
     *     description="Favorite and unfavorite",
     *     operationId="api.favorites.post-favorite",
     *     produces={"application/json"},
     *     tags={"Favorites Auth User"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
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
     *     description="player, team, school",
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
    public function postFavorites(Request $request)
    {
        $user = $this->auth->user();
        $modelType = Base::TYPES[$request['modelType']];
        if (empty($user)) {
            return $this->response->array(['success' => false]);
        }

        Favorite::postFavorites($user, $request, $modelType);

        return $this->response->array(['success' => true]);
    }

    /**
     * Favorites list
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/favorites/get-favorite",
     *     description="Favorites list",
     *     operationId="api.favorites.get-favorite",
     *     produces={"application/json"},
     *     tags={"Favorites Auth User"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getFavorites(Request $request)
    {
        $user = $this->auth->user();
        if (empty($user)) {
            return $this->response->array(['success' => false]);
        }

        $list = Favorite::getFavoritesList($user, $request->get('seasonId'));

        return $this->response->array($list);
    }
}
