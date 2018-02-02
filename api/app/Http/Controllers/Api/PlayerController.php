<?php

namespace App\Http\Controllers\Api;

use App\Models\Favorite;
use App\Models\Game;
use App\Models\Player;
use App\Models\School;
use App\Models\Search;
use App\Models\Sports;
use App\Transformers\PlayerAboutTransformer;
use App\Transformers\PlayerTransformer;
use Dingo\Api\Http\Request;
use sngrl\SphinxSearch\SphinxSearch;
use Sphinx\SphinxClient;

/**
 * Class PlayerController
 *
 * @package App\Http\Controllers\Api
 */
class PlayerController extends ApiController
{
    /**
     * Get player
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/player",
     *     description="Get player",
     *     operationId="api.player",
     *     produces={"application/json"},
     *     tags={"Player"},
     *     @SWG\Parameter(
     *     name="playerId",
     *     in="query",
     *     description="player ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="sizeSystem",
     *     in="query",
     *     description="size system name",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function index(Request $request)
    {
        $commonTeam = Player::active()->where(['id' => $request->get('playerId')])->first();

        // TODO: потім забрати з запиту дані будуть тянутись з сервера
        $commonTeam['sizeSystem'] = $request->get('sizeSystem');

        return $this->response->item($commonTeam, new PlayerTransformer);
    }

    /**
     * About
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/player/about",
     *     description="About",
     *     operationId="api.player.about",
     *     produces={"application/json"},
     *     tags={"Player"},
     *     @SWG\Parameter(
     *     name="playerId",
     *     in="query",
     *     description="player ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="sizeSystem",
     *     in="query",
     *     description="size system name",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getAbout(Request $request)
    {
        $player = Player::active()->where(['id' => $request->get('playerId')])->first();

        // TODO: потім забрати з запиту дані будуть тянутись з сервера
        $player['sizeSystem'] = $request->get('sizeSystem');

        return $this->response->item($player, new PlayerAboutTransformer);
    }

    /**
     * About - stats
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/player/about-stats",
     *     description="About - stats",
     *     operationId="api.player.about.stats",
     *     produces={"application/json"},
     *     tags={"Player"},
     *     @SWG\Parameter(
     *     name="playerId",
     *     in="query",
     *     description="player ID",
     *     required=true,
     *     type="integer"
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
    public function getAboutSeasonStats(Request $request)
    {
        $sport = Sports::getSportGameStats($request['sportId']);
        $pIds = Player::getPlayerList($request->get('playerId'));
        $sportClass = (new $sport['sportClass']);

        $stats = Game::getPlayerSeasonGamesStats($sport['sportClass'], $sport['tableSport'], $request);
        $statData = $sportClass->getPlayerStandings($pIds, null);

        $data['personalResult'] = $sportClass::personalResultStats($statData);
        $data['personalResult']['columns'] = Game::getColumnsData($sportClass::$personalAttr);
        $data['personalResult']['poweredBy'] = School::getPoweredBy((!empty($statData)) ? $statData[0]->school_id : []);

        $data['seasonStats'] = $sportClass::playerSeasonStats($stats);
        $data['seasonStats']['allCount'] = count($stats);
        $data['seasonStats']['columns'] = Game::getColumnsData($sportClass::$seasonAttr);
        $schoolId = ($stats->isNotEmpty()) ? $stats[0]->game->team->school->id : [];

        $data['seasonStats']['poweredBy'] = School::getPoweredBy($schoolId);

        return $this->response->array($data);
    }

    /**
     * Player Standings
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/player/player-standings",
     *     description="Player Standings",
     *     operationId="api.player.player.standings",
     *     produces={"application/json"},
     *     tags={"Player"},
     *     @SWG\Parameter(
     *     name="playerId",
     *     in="query",
     *     description="player ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="viewType",
     *     in="query",
     *     description="state || school || team",
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
    public function getPlayerStandings(Request $request)
    {
        $limit = 10;
        $player = Player::getPlayerData($request);
        $sport = Sports::getSportGameStats($player->sport_id);
        $sportClass = (new $sport['sportClass']);
        $statsObj = Player::getInfoPlayerStanding($sportClass, $sport, $player, $request);

        $statsArr = $statsObj->toArray();
        $stats = array_keys($statsArr);
        $playerPosition = array_search($request->get('playerId'), $stats);
        $pageNum = ceil(($playerPosition + 1) / $limit);

        $statData = Player::getPagePlayerStandings($pageNum, $limit, $sportClass, $statsObj);

        $pages = [];
        foreach ($statsObj->chunk($limit)->toArray() as $key => $item) {
            $new = array_values($item);
            $page = $key + 1;

            $pages[] = [
                'page' => $page,
                'max' => array_first($new),
                'min' => end($new),
                'stats' => ($page == $pageNum) ? $sportClass::playerStandingStats($statData) : []
            ];
        }

        $data['columns'] = Game::getColumnsData($sportClass::$personalAttr);
        $data['poweredBy'] = School::getPoweredBy((!empty($player)) ? $player->school_id : []);
        $data['pages'] = $pages;

        return $this->response->array($data);
    }

    /**
     * Get Player Standings - pagination number
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/player/player-standings-page",
     *     description="Get Player Standings - pagination number",
     *     operationId="api.player.player.standings.page",
     *     produces={"application/json"},
     *     tags={"Player"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="playerId",
     *     in="query",
     *     description="player ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="viewType",
     *     in="query",
     *     description="state || school || team",
     *     default="state",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page",
     *     default=2,
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="isFavorite",
     *     in="query",
     *     description="use favorite filter",
     *     required=true,
     *     default=false,
     *     type="boolean"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getPlayerStandingsPage(Request $request)
    {
        $limit = 10;
        $isFavorite = false;
        $user = $this->auth->user();
        $player = Player::getPlayerData($request);
        $sport = Sports::getSportGameStats($player->sport_id);
        $sportClass = (new $sport['sportClass']);
        $statsObj = Player::getInfoPlayerStanding($sportClass, $sport, $player, $request);

        if (!empty($user)) {
            if (is_string($request->get('isFavorite')) && $request->get('isFavorite') == 'true') {
                $isFavorite = true;
            } elseif (is_string($request->get('isFavorite')) && $request->get('isFavorite') == 'false') {
                $isFavorite = false;
            } else {
                $isFavorite = $request->get('isFavorite');
            }
        }
        if ($isFavorite) {
            $favorites = Favorite::where([
                'user_id' => $user->id,
                'model_type' => Player::class,
                'season_id' => $player->season_id,
                'sport_id' => $player->sport_id
            ])->pluck('model_id')->toArray();

            $statsArr = $statsObj->toArray();
            $stats = array_keys($statsArr);
            $uniqueIds = Search::getUniqueList($favorites, $stats, false);

            $statsObjNew = [];
            foreach ($statsArr as $key => $val) {
                if (in_array($key, $uniqueIds)) {
                    $statsObjNew[$key] = $val;
                }
            }
            $statsObj = collect($statsObjNew);
        }

        $statData = Player::getPagePlayerStandings($request->get('page'), $limit, $sportClass, $statsObj);

        $data['stats'] = $sportClass::playerStandingStats($statData);

        return $this->response->array($data);

    }

    /**
     * Player Standings - Search
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/player/player-standings-search",
     *     description="Player Standings - Search",
     *     operationId="api.player.player.standings.search",
     *     produces={"application/json"},
     *     tags={"Player"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="playerId",
     *     in="query",
     *     description="player ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="viewType",
     *     in="query",
     *     description="state || school || team",
     *     required=true,
     *     default="state",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="q",
     *     in="query",
     *     description="query search",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="isFavorite",
     *     in="query",
     *     description="use favorite filter",
     *     required=true,
     *     default=false,
     *     type="boolean"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getPlayerStandingsSearch(Request $request)
    {
        $limit = 10;
        $idList = [];
        $isFavorite = false;
        $user = $this->auth->user();
        $player = Player::getPlayerData($request);
        $sport = Sports::getSportGameStats($player->sport_id);
        $sportClass = (new $sport['sportClass']);
        $statsObj = Player::getInfoPlayerStanding($sportClass, $sport, $player, $request);

        if (!empty($user)) {
            if (is_string($request->get('isFavorite')) && $request->get('isFavorite') == 'true') {
                $isFavorite = true;
            } elseif (is_string($request->get('isFavorite')) && $request->get('isFavorite') == 'false') {
                $isFavorite = false;
            } else {
                $isFavorite = $request->get('isFavorite');
            }
        }
        if ($isFavorite) {
            $favorites = Favorite::where([
                'user_id' => $user->id,
                'model_type' => Player::class,
                'season_id' => $player->season_id,
                'sport_id' => $player->sport_id
            ])->pluck('model_id')->toArray();

            $idList = $favorites;
        }
        if (!empty($request->get('q'))) {
            $playerDB = (new SphinxSearch)->search($request->get('q'), 'player_standings')->setFieldWeights([
                'user_name' => 10,
                'team_name' => 9,
                'number' => 8
            ])->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(9999)->query();

            if (!empty($playerDB['matches'])) {
                $players = array_keys($playerDB['matches']);
                $idList = $players;
            }
        }
        if ($isFavorite && !empty($request->get('q'))) {
            $idList = Search::getUniqueList($favorites, $players, false);
        }

        $statsArr = $statsObj->toArray();
        $stats = array_keys($statsArr);
        $uniqueIds = Search::getUniqueList($idList, $stats, false);
        $statsObjNew = [];
        foreach ($statsArr as $key => $val) {
            if (in_array($key, $uniqueIds)) {
                $statsObjNew[$key] = $val;
            }
        }
        $statsNew = array_keys($statsObjNew);
        $statsObjNew = collect($statsObjNew);
        $playerPosition = array_search($request->get('playerId'), $statsNew);
        $pageNum = ceil(($playerPosition + 1) / $limit);
        $statData = Player::getPagePlayerStandings($pageNum, $limit, $sportClass, $statsObjNew);

        $pages = [];
        foreach ($statsObjNew->chunk($limit)->toArray() as $key => $item) {
            $new = array_values($item);
            $page = $key + 1;

            $pages[] = [
                'page' => $page,
                'max' => array_first($new),
                'min' => end($new),
                'stats' => ($page == $pageNum) ? $sportClass::playerStandingStats($statData) : []
            ];
        }

        $data['columns'] = Game::getColumnsData($sportClass::$personalAttr);
        $data['poweredBy'] = School::getPoweredBy((!empty($player)) ? $player->school_id : []);
        $data['pages'] = $pages;

        return $this->response->array($data);
    }
}