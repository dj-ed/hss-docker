<?php

namespace App\Http\Controllers\Api;

use App\Models\Sports;
use App\Models\Game;
use App\Models\Player;
use App\Models\TeamType;
use App\Transformers\ScheduleGameTransformer;
use Dingo\Api\Http\Request;

/**
 * Class StatisticsController
 *
 * @package App\Http\Controllers\Api
 */
class StatisticsController extends ApiController
{
    /**
     * Stats - Leaderboard
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/statistics/leaderboard",
     *     description="Stats - Leaderboard",
     *     operationId="api.statistics.leaderboard",
     *     produces={"application/json"},
     *     tags={"Statistics"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
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
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="varsityId",
     *     in="query",
     *     description="varsity ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="teamId",
     *     in="query",
     *     description="team ID",
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
    public function leaderboard(Request $request)
    {
        $request = $request->all();
        $ids = Player::getPlayersList($request);
        $sport = Sports::where('id', $request['sportId'])->first();

        if (!empty($ids) && !empty($sport)) {
            $sportClass = 'App\Models\GamePlayerStat' . $sport->title;
            $rez['stats'] = (new $sportClass)->getPlayersLeaderBoard($ids);
            $rez['innerColumns'] = array_keys($sportClass::$additionalAttr);
            $rez['innerColumnsName'] = array_values($sportClass::$additionalAttr);

            return $this->response->array($rez);
        }
    }

    /**
     * Stats - Scoreboard
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/statistics/scoreboard",
     *     description="Stats - Scoreboard",
     *     operationId="api.statistics.scoreboard",
     *     produces={"application/json"},
     *     tags={"Statistics"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
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
     *     name="teamId",
     *     in="query",
     *     description="team ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page",
     *     required=true,
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
    public function scoreboard(Request $request)
    {
        $limit = 30;
        $request = $request->all();
        $rez = Game::getScoreboard($request, $limit);

        return $this->response->paginator($rez, new ScheduleGameTransformer);
    }

    /**
     * Stats - Sport Scoreboard
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/statistics/sport-scoreboard",
     *     description="Stats - Sport Scoreboard",
     *     operationId="api.statistics.sport-scoreboard",
     *     produces={"application/json"},
     *     tags={"Statistics"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
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
     *     name="genderId",
     *     in="query",
     *     description="gender ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="teamType",
     *     in="query",
     *     description="team TYPE",
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
    public function sportScoreboard(Request $request)
    {
        $limit = 10;
        $scoreboardData = Game::getSportScoreboard($request, $limit);

        return $this->response->array($scoreboardData);
    }

    /**
     * Stats - Sport Leaderboard
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/statistics/sport-leaderboard",
     *     description="Stats - Sport Leaderboard",
     *     operationId="api.statistics.sport-leaderboard",
     *     produces={"application/json"},
     *     tags={"Statistics"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
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
     *     name="genderId",
     *     in="query",
     *     description="gender ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="teamType",
     *     in="query",
     *     description="team TYPE",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="withoutTypes",
     *     in="query",
     *     description="without TYPE",
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
    public function sportLeaderboard(Request $request)
    {
        $request = $request->all();
        if (empty($request['withoutTypes'])) {
            $rez['teams'] = $teams = TeamType::teamTypeByGender($request);
        }
        if (empty($request['genderId'])) {
            $request['genderId'] = $teams[0]['gender']['id'];
        }
        if (empty($request['varsityId'])) {
            $request['varsityId'] = $teams[0]['teamType']['id'];
        }

        $ids = Player::getPlayersList($request);
        $sport = Sports::where('id', $request['sportId'])->first();

        if (!empty($ids) && !empty($sport)) {
            $sportClass = 'App\Models\GamePlayerStat' . $sport->title;
            $rez['stats'] = (new $sportClass)->getPlayersLeaderBoard($ids, null, 5);
        }
        return $this->response->array($rez);
    }

    /**
     * Stats - Sport Team Standings
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/statistics/sport-team-standings",
     *     description="Stats - Sport Team Standings",
     *     operationId="api.statistics.sport-team-standings",
     *     produces={"application/json"},
     *     tags={"Statistics"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
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
     *     name="genderId",
     *     in="query",
     *     description="gender ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="teamType",
     *     in="query",
     *     description="team TYPE",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="withoutTypes",
     *     in="query",
     *     description="without TYPE",
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
    public function sportTeamStandings(Request $request)
    {
        $limit = 10;

        $request = $request->all();
        if (empty($request['withoutTypes'])) {
            $rez['teamTypes'] = $teams = TeamType::teamTypeByGender($request);
        }
        if (empty($request['genderId'])) {
            $request['genderId'] = $teams[0]['gender']['id'];
        }
        if (empty($request['varsityId'])) {
            $request['varsityId'] = $teams[0]['teamType']['id'];
        }
        $rez['teams'] = Game::getFullBoard($request, $limit)->items();

        return $this->response->array($rez);
    }

    /**
     * Stats - Scoreboard detail
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/statistics/scoreboard-game-detail",
     *     description="Stats - Scoreboard detail",
     *     operationId="api.statistics.scoreboard-game-detail",
     *     produces={"application/json"},
     *     tags={"Statistics"},
     *     @SWG\Parameter(
     *     name="gameId",
     *     in="query",
     *     description="game ID",
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
    public function scoreboardGameDetail(Request $request)
    {
        $request = $request->all();
        $game = Game::where('id', $request['gameId'])->first();
        $sportClass = 'App\Models\GamePlayerStat' . $game->team->sport->title;
        $rez = $sportClass::getPlayersStats($game);

        return $this->response->array($rez);
    }

    /**
     * Stats - Full Board
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/statistics/full-board",
     *     description="Stats - Full Board",
     *     operationId="api.statistics.full-board",
     *     produces={"application/json"},
     *     tags={"Statistics"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
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
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="varsityId",
     *     in="query",
     *     description="varsity ID",
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
    public function fullBoard(Request $request)
    {
        $limit = 30;
        $fullBoard = Game::getFullBoard($request->all(), $limit);

        return $this->response->array($fullBoard);
    }

    /**
     * Stats - Team Standings
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/statistics/standings",
     *     description="Stats - Team Standings",
     *     operationId="api.statistics.standings",
     *     produces={"application/json"},
     *     tags={"Statistics"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
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
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="teamId",
     *     in="query",
     *     description="team ID",
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
    public function standings(Request $request)
    {
        $standings = Game::getTeamStandings($request->all());

        return $this->response->array($standings);
    }
}