<?php

namespace App\Http\Controllers\Api;

use App\Models\Game;
use App\Transformers\ScheduleGameTransformer;
use App\Transformers\ScheduleYearTransformer;
use App\Transformers\UpcomingGameTransformer;
use Dingo\Api\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

/**
 * Class GameController
 *
 * @package App\Http\Controllers\Api
 */
class GameController extends ApiController
{
    /**
     * Upcoming game
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/game/upcoming",
     *     description="Upcoming game",
     *     operationId="api.game.upcoming",
     *     produces={"application/json"},
     *     tags={"Game"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="teamId",
     *     in="query",
     *     description="ID team",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="schoolId",
     *     in="query",
     *     description="ID school",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="schoolId",
     *     in="query",
     *     description="ID school",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="ID state",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="ID city",
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
    public function upcoming(Request $request)
    {
        $games = Game::getUpcomingGames($request->all());

        return $this->response->collection($games, new UpcomingGameTransformer());
    }

    /**
     * Games Schedule - Today
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/game/schedule-today",
     *     description="Games Schedule - Today",
     *     operationId="api.game.schedule-today",
     *     produces={"application/json"},
     *     tags={"Game"},
     *     @SWG\Parameter(
     *     name="date",
     *     in="query",
     *     description="Y-m-d",
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
     *     name="teamId",
     *     in="query",
     *     description="team ID",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="schoolId",
     *     in="query",
     *     description="school ID",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="ID state",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="ID city",
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
    public function scheduleToday(Request $request)
    {
        $games = Game::getDayGames($request->all());

        return $this->response->collection($games, new ScheduleGameTransformer());
    }

    /**
     * Games Schedule - Calendar
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/game/schedule-calendar",
     *     description="Games Schedule - Calendar",
     *     operationId="api.game.schedule-calendar",
     *     produces={"application/json"},
     *     tags={"Game"},
     *     @SWG\Parameter(
     *     name="calendarType",
     *     in="query",
     *     description="week || month || year",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="startDate",
     *     in="query",
     *     description="Y-m-d",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="endDate",
     *     in="query",
     *     description="Y-m-d",
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
     *     name="teamId",
     *     in="query",
     *     description="team ID",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="schoolId",
     *     in="query",
     *     description="school ID",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="ID state",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="ID city",
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
    public function scheduleCalendar(Request $request)
    {
        $request = $request->all();
        if (isset($request['calendarType'])) {
            switch ($request['calendarType']) {
                case 'week':
                case 'month':
                    $games = Game::getDateInterval($request);

                    return $this->response->collection($games, new ScheduleGameTransformer());
                    break;
                case 'year':
                    $games = Game::getYearGames($request);

                    return $this->response->collection($games, new ScheduleYearTransformer());
                    break;
            }
        }
    }

    /**
     * Games Schedule - Full Timetable
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/game/schedule-full",
     *     description="Games Schedule - Full Timetable",
     *     operationId="api.game.schedule-full",
     *     produces={"application/json"},
     *     tags={"Game"},
     *     @SWG\Parameter(
     *     name="allGames",
     *     in="query",
     *     description="0 - limit 30 || 1 - all",
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
     *     name="teamId",
     *     in="query",
     *     description="team ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="schoolId",
     *     in="query",
     *     description="school ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="ID state",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="ID city",
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
    public function scheduleFull(Request $request)
    {
        $request = $request->all();
        $rez = Game::getFullTimeTable($request);

        $manager = new Manager();
        $games= $manager->createData(new Collection($rez['games'],new ScheduleGameTransformer()))->toArray();


        return $this->response->array([
            'data' => $games['data'],
            'remainder' => $rez['remainder']
        ]);
    }
}