<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Models\Player;
use App\Models\Sports;
use App\Models\Team;
use App\Models\TeamCoach;
use App\Transformers\CoachCornerTransformer;
use App\Transformers\ScheduleGameTransformer;
use App\Transformers\TeamAllTransformer;
use App\Transformers\TeamShortTransformer;
use App\Transformers\TeamTransformer;
use Dingo\Api\Http\Request;

/**
 * Class TeamController
 *
 * @package App\Http\Controllers\Api
 */
class TeamController extends ApiController
{
    /**
     * Team - get by ID
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/team",
     *     description="Team - get by ID",
     *     operationId="api.team",
     *     produces={"application/json"},
     *     tags={"Team"},
     *     @SWG\Parameter(
     *     name="teamId",
     *     in="query",
     *     description="team ID",
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
    public function index(Request $request)
    {
        $commonTeam = Team::active()->where(['id' => $request->get('teamId')])->first();
        return $this->response->item($commonTeam, new TeamTransformer($request->get('stateId'), $request->get('cityId')));
    }

    /**
     * Teams short list
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/all-teams/short-list",
     *     description="Teams",
     *     operationId="api.all-teams.short-list",
     *     produces={"application/json"},
     *     tags={"Team"},
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
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function shortList(Request $request)
    {
        $teams = Team::active()->where([
            'sport_id' => $request->get('sportId'),
            'season_id' => $request->get('seasonId'),
        ]);

        if ($request->get('genderId')) {
            $gender = $request->get('genderId');
            $teams->where(['gender_id' => $gender]);
        }

        $teams = $teams->get();
        return $this->response->collection($teams, new TeamShortTransformer);
    }

    /**
     * Home Court - Game Recap
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/team/game-recap",
     *     description="Home Court - Game Recap",
     *     operationId="api.team.game-recap",
     *     produces={"application/json"},
     *     tags={"Team"},
     *     @SWG\Parameter(
     *     name="teamId",
     *     in="query",
     *     description="team ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="gameId",
     *     in="query",
     *     description="game ID",
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
    public function gameRecap(Request $request)
    {
        $games = Game::getRecapGames($request->all());
        $rez = [];

        if ($games) {
            foreach ($games as $key => $game) {
                if ($game) {
                    $rez['games'][$key] = (new ScheduleGameTransformer())->innerTransform($game);
                } else {
                    $rez['games'][$key] = null;
                }
            }
        }

        if (!empty($games['currentGame'])) {
            $rez['gameMedia'] = $games['currentGame']->getGameMedia();
        }

        return $this->response->array($rez);
    }

    /**
     * Home Court - Game Recap details
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/team/game-recap-detail",
     *     description="Home Court - Game Recap details",
     *     operationId="api.team.game-recap-detail",
     *     produces={"application/json"},
     *     tags={"Team"},
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
    public function gameRecapDetail(Request $request)
    {
        $rez = [];
        $game = Game::where('id', $request['gameId'])->first();
        $news = $game->news()->first();
        $pStatClass = 'App\Models\GamePlayerStat' . ucfirst($game->team->sport->title);
        $gScoreClass = 'App\Models\GameScoreboard' . ucfirst($game->team->sport->title);

        $rez['scoreboard'] = $gScoreClass::getGameScoreboard($game);
        $rez['topGamePlayers'] = $pStatClass::getTopGamePlayers($game, 3);
        $rez['topPlayers'] = Game::getGameRecapLeaderboard($game, $pStatClass);
        $rez['newsId'] = (!empty($news)) ? $news->id : null;

        return $this->response->array($rez);
    }

    /**
     * Home Court - Coach Corner
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/team/coach-corner",
     *     description="Home Court - Coach Corner",
     *     operationId="api.team.coach-corner",
     *     produces={"application/json"},
     *     tags={"Team"},
     *     @SWG\Parameter(
     *     name="teamId",
     *     in="query",
     *     description="team ID",
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
    public function coachCorner(Request $request)
    {
        $coach = TeamCoach::coachHead()->where(['team_id' => $request->get('teamId')])->first();

        return $this->response->item($coach, new CoachCornerTransformer);
    }

    /**
     * Roster List
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/team/roster",
     *     description="Roster List",
     *     operationId="api.team.roster",
     *     produces={"application/json"},
     *     tags={"Team"},
     *     @SWG\Parameter(
     *     name="teamId",
     *     in="query",
     *     description="team ID",
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
    public function teamRoster(Request $request)
    {
        $team = Team::with('users')->where('id', $request['teamId'])->first();
        $players = Player::active()->with('user')->with('xPlayerPositions')->whereIn('team_id', [$team->id])->get();
        $sport = Sports::where('id', $request['sportId'])->first();
        $coaches = $team->teamCoaches;

        if (!empty($sport)) {
            $sportClass = 'App\Models\GamePlayerStat' . $sport->title;
        }

        foreach ($players as $player) {
            if (!empty($sport)) {
                $stats = [
                    'innerColumns' => array_keys($sportClass::$rosterAttr),
                    'innerColumnsName' => array_values($sportClass::$rosterAttr),
                    'data' => (new $sportClass)->getTeamRosterStatistics($player->id)
                ];
            }

            $teamPlayer[] = [
                'id' => $player->id,
                'name' => $player->user->first_name . ' ' . $player->user->last_name,
                'userPhotoUrl' => $player->user->getPhoto(),
                'number' => $player->number,
                'positions' => $player->getPositions(true, true),
                'height' => ($player->metrics->height) ? $player->metrics->height . '\'' : null,
                'height_in' => ($player->metrics->height_in) ? $player->metrics->height_in . '"' : null,
                'weight' => ($player->metrics->weight) ? $player->metrics->weight . ' LBS' : null,
                'stats' => (!empty($sport)) ? $stats : []
            ];
        }

        if (!empty($coaches)) {
            foreach ($coaches as $coach) {
                $teamCoach[] = [
                    'id' => $coach->id,
                    'name' => $coach->user->first_name . ' ' . $coach->user->last_name,
                    'type' => $coach->coach_type,
                    'userPhotoUrl' => $coach->user->getPhoto()
                ];
            }
        }

        $data = [
            'players' => (!empty($teamPlayer)) ? $teamPlayer : [],
            'coaches' => (!empty($teamCoach)) ? $teamCoach : []
        ];

        return $this->response->array($data);
    }

    /**
     * List states and county from all teams page
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/all-teams/full-list",
     *     description="List states and county from all teams page",
     *     operationId="api.all-teams.full-list",
     *     produces={"application/json"},
     *     tags={"Team"},
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
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getAllList(Request $request)
    {
        $teams = Team::getAllTeamList($request->get('seasonId'), $request->get('sportId'));
        $allTeams = Team::generateResponseData($teams);

        return $this->response->array($allTeams);
    }

    /**
     * Team List
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/all-teams/full-list-teams",
     *     description="Team List",
     *     operationId="api.all-teams.full-list-teams",
     *     produces={"application/json"},
     *     tags={"Team"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="schoolId",
     *     in="query",
     *     description="school ID",
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
    public function getAllTeams(Request $request)
    {
        $query = Team::active()->with('teamType')->where([
            'season_id' => $request->get('seasonId'),
            'school_id' => $request->get('schoolId')
        ]);

        if (!empty($request->get('sportId'))) {
            $query = $query->where(['sport_id' => $request->get('sportId')]);
        }

        $teams = $query->get();

        return $this->response->collection($teams, new TeamAllTransformer);
    }
}