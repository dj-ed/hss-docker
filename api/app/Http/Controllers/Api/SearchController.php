<?php

namespace App\Http\Controllers\Api;

use App\Models\Search;
use Dingo\Api\Http\Request;

/**
 * Class SearchController
 *
 * @package App\Http\Controllers\Api
 */
class SearchController extends ApiController
{
    /**
     * All search
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/search",
     *     description="All search",
     *     operationId="api.search",
     *     produces={"application/json"},
     *     tags={"Search"},
     *     @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     description="search query",
     *     required=true,
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
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="state ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="city ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getIndex(Request $request)
    {
        $all = Search::generalSearch($request->get('q'), $request->get('seasonId'), $request->get('sportId'), $request->get('stateId'), $request->get('cityId'));
        $result = Search::getCountSearch($all);

        return $this->response->array($result);
    }

    /**
     * News search
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/search/news",
     *     description="News search",
     *     operationId="api.search.news",
     *     produces={"application/json"},
     *     tags={"Search"},
     *     @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     description="search query",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number pagination",
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
    public function getNews(Request $request)
    {
        $news = Search::getSearchNews($request->get('q'), $request->get('sportId'), $request->get('page'));

        return $this->response->array($news);
    }

    /**
     * Player search
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/search/player",
     *     description="Player search",
     *     operationId="api.search.player",
     *     produces={"application/json"},
     *     tags={"Search"},
     *     @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     description="search query",
     *     required=true,
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
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="state ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="city ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number pagination",
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
    public function getPlayers(Request $request)
    {
        $player = Search::getSearchPlayers($request->get('q'), $request->get('seasonId'), $request->get('sportId'), $request->get('stateId'), $request->get('cityId'), $request->get('page'));

        return $this->response->array($player);
    }

    /**
     * Coach search
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/search/coach",
     *     description="Coach search",
     *     operationId="api.search.coach",
     *     produces={"application/json"},
     *     tags={"Search"},
     *     @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     description="search query",
     *     required=true,
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
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="state ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="city ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number pagination",
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
    public function getCoaches(Request $request)
    {
        $coach = Search::getSearchCoaches($request->get('q'), $request->get('seasonId'), $request->get('sportId'), $request->get('stateId'), $request->get('cityId'), $request->get('page'));

        return $this->response->array($coach);
    }

    /**
     * School search
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/search/school",
     *     description="School search",
     *     operationId="api.search.school",
     *     produces={"application/json"},
     *     tags={"Search"},
     *     @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     description="search query",
     *     required=true,
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
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="state ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="city ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number pagination",
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
    public function getSchools(Request $request)
    {
        $school = Search::getSearchSchools($request->get('q'), $request->get('seasonId'), $request->get('sportId'), $request->get('stateId'), $request->get('cityId'), $request->get('page'));

        return $this->response->array($school);
    }

    /**
     * Team search
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/search/team",
     *     description="Team search",
     *     operationId="api.search.team",
     *     produces={"application/json"},
     *     tags={"Search"},
     *     @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     description="search query",
     *     required=true,
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
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="state ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="city ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number pagination",
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
    public function getTeams(Request $request)
    {
        $team = Search::getSearchTeams($request->get('q'), $request->get('seasonId'), $request->get('sportId'), $request->get('stateId'), $request->get('cityId'), $request->get('page'));

        return $this->response->array($team);
    }

    /**
     * Game search
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/search/games",
     *     description="Game search",
     *     operationId="api.search.games",
     *     produces={"application/json"},
     *     tags={"Search"},
     *     @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     description="search query",
     *     required=true,
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
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="state ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="city ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number pagination",
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
    public function getGames(Request $request)
    {
        $game = Search::getSearchGame($request->get('q'), $request->get('seasonId'), $request->get('sportId'), $request->get('stateId'), $request->get('cityId'), $request->get('page'));

        return $this->response->array($game);
    }

    /**
     * Media search
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/search/media",
     *     description="Media search",
     *     operationId="api.search.media",
     *     produces={"application/json"},
     *     tags={"Search"},
     *     @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     description="search query",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="mediaType",
     *     in="query",
     *     description="default = all, photo || video || album",
     *     required=true,
     *     default="all",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="state ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="city ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="default = 1,page number pagination",
     *     required=true,
     *     default=1,
     *     type="integer"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getMedia(Request $request)
    {
        $media = Search::getSearchMedia($request->get('q'), $request->get('seasonId'), $request->get('sportId'), $request->get('stateId'), $request->get('cityId'), $request->get('page'), $request->get('mediaType'));

        return $this->response->array($media);
    }
}
