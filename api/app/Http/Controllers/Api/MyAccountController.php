<?php

namespace App\Http\Controllers\Api;

use App\Models\Base;
use App\Models\Comments;
use App\Models\CommentVotes;
use App\Models\Event;
use App\Models\EventsLog;
use App\Models\Favorite;
use App\Models\Like;
use App\Models\News;
use App\Models\Player;
use App\Models\School;
use App\Models\ScrapBook;
use App\Models\Search;
use App\Models\Sports;
use App\Models\Team;
use App\Transformers\EventsLogTransformer;
use App\Transformers\EventsUpcomingTransformer;
use App\Transformers\UserSettingsTransformer;
use App\Transformers\UserTransformer;
use Carbon\Carbon;
use Dingo\Api\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

/**
 * Class MyAccountController
 *
 * @package App\Http\Controllers\Api
 */
class MyAccountController extends ApiController
{
    /**
     * My Account - index
     *
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/my-account",
     *     description="My Account - index",
     *     operationId="api.my-account",
     *     produces={"application/json"},
     *     tags={"My Account"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function index()
    {
        return $this->response->item($this->auth->user(), new UserTransformer);
    }

    /**
     * My Account - get settings
     *
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/my-account/get-settings",
     *     description="My Account - get settings",
     *     operationId="api.my-account.get-settings",
     *     produces={"application/json"},
     *     tags={"My Account"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getSettings()
    {
        return $this->response->item($this->auth->user(), new UserSettingsTransformer);
    }

    /**
     * My Account - get favorites
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/my-account/get-favorites",
     *     description="My Account - get favorites",
     *     operationId="api.my-account.get-favorites",
     *     produces={"application/json"},
     *     tags={"My Account"},
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
     *     name="modelType",
     *     in="query",
     *     description="player, team, school",
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
     *     description="page number",
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
    public function getFavorites(Request $request)
    {
        $perPage = 10;
        $user = $this->auth->user();
        $modelType = Base::TYPES[$request['modelType']];
        $favorites = Favorite::getFavorites($user, $request->get('seasonId'));
        $query = $favorites->where('model_type', $modelType);
        if (!empty($request->get('sportId')) && $modelType != School::class) {
            $query = $query->where('sport_id', $request->get('sportId'));
        }
        $modelId = $query->pluck('model_id');
        $data = Search::generateCustomCollection($modelId, $perPage, $request->get('page'));

        $sameTransData = [];
        switch ($modelType) {
            case Player::class:
                if (!empty($data['chunk'])) {
                    $sameTransData = Player::getPlayersFromFavorites($data['chunk']);
                }
                break;
            case Team::class:
                if (!empty($data['chunk'])) {
                    $sameTransData = Team::getTeamsFromFavorites($data['chunk']);
                }
                break;
            case School::class:
                if (!empty($data['chunk'])) {
                    $sameTransData = School::getSchoolsFromFavorites($data['chunk'], $request);
                }
                break;
        }
        $sportsId = Favorite::getUniqueSportIdFromFavorites($favorites);
        $sameTransData['sports'] = Sports::getSports($sportsId)->toArray();
        $sameTransData['pagination'] = Search::generateCustomPagination($data['total'], $data['count'], $perPage, $request->get('page'), $data['total_pages']);

        return $this->response->array($sameTransData);
    }

    /**
     * My Account - get events calendar
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/my-account/get-events-calendar",
     *     description="My Account - get events calendar",
     *     operationId="api.my-account.get-events-calendar",
     *     produces={"application/json"},
     *     tags={"My Account"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="calendarType",
     *     in="query",
     *     description="week || month || year",
     *     default="year",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="startDate",
     *     in="query",
     *     description="Y-m-d",
     *     default="2016-01-01",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="endDate",
     *     in="query",
     *     description="Y-m-d",
     *     default="2017-12-31",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     default=1,
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="modelType",
     *     in="query",
     *     description="game",
     *     default="game",
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
    public function getEventsCalendar(Request $request)
    {
        $user = $this->auth->user();
        $modelType = Base::TYPES[$request['modelType']];
        $start_date = Carbon::parse($request['startDate'])->format('Y-m-d');
        $end_date = Carbon::parse($request['endDate'])->format('Y-m-d');

        $sportsId = Event::getSportIdList($modelType, $user->id);
        $sports = Sports::getSports($sportsId)->toArray();

        $upcomingData['data'] = [];
        $upcoming = Event::getUpcomingEvent($modelType, $user->id, $request['sportId']);
        if (!empty($upcoming)) {
            $upcomingData = (new Manager)->createData(new Item($upcoming, new EventsUpcomingTransformer()))->toArray();
        }

        $eventsData['data'] = [];
        if (isset($request['calendarType'])) {
            $eventsData = Event::getEventsCalendar($request['calendarType'], $modelType, $user->id, $request['sportId'], $start_date, $end_date);
        }

        $result['sports'] = $sports;
        $result['upcoming'] = $upcomingData;
        $result['calendar'] = $eventsData;

        return $this->response->array($result);
    }

    /**
     * My Account - get events log
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/my-account/get-events-log",
     *     description="My Account - get events log",
     *     operationId="api.my-account.get-events-log",
     *     produces={"application/json"},
     *     tags={"My Account"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number",
     *     default=1,
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="tabs",
     *     in="query",
     *     description="all || comments || events || likes || tags || publishing",
     *     default="all",
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
    public function getEventsLog(Request $request)
    {
        $user = $this->auth->user();
        $limit = 10;
        $query = EventsLog::with('fromUser')->where(['user_id' => $user->id]);
        $data = collect([]);
        switch ($request->get('tabs')) {
            case 'all':
                $data = $query->game()->latest()->paginate($limit, ['*'], 'page', $request->get('page'));
                break;
            case 'comments':
                $data = $query->where(['model_type' => Comments::class])->latest()
                              ->paginate($limit, ['*'], 'page', $request->get('page'));
                break;
            case 'events':
                $data = $query->game()->where(['model_type' => Event::class])->latest()
                              ->paginate($limit, ['*'], 'page', $request->get('page'));
                break;
            case 'likes':
                $data = $query->whereIn('model_type', [Like::class, CommentVotes::class])->latest()
                              ->paginate($limit, ['*'], 'page', $request->get('page'));
                break;
            case 'tags':

                break;
            case 'publishing':

                break;
        }

        return $this->response->paginator($data, new EventsLogTransformer);
    }

    /**
     * My Account - post events log mark all as read or one read
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/my-account/post-events-log-read",
     *     description="My Account - post events log mark all as read or one read",
     *     operationId="api.my-account.post-events-log-read",
     *     produces={"application/json"},
     *     tags={"My Account"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="tabs",
     *     in="query",
     *     description="all || comments || events || likes || tags || publishing",
     *     default="all",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="modelId",
     *     in="query",
     *     description="ID or array list ID",
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
    public function postEventsLogRead(Request $request)
    {
        $user = $this->auth->user();

        $query = EventsLog::with('fromUser')->where(['user_id' => $user->id]);
        if (!empty($request->get('modelId')) && is_array($request->get('modelId'))) {
            $query = $query->whereIn('model_id', $request->get('modelId'));
        } elseif (!empty($request->get('modelId'))) {
            $query = $query->where(['model_id' => $request->get('modelId')]);
        }

        $events = [];
        switch ($request->get('tabs')) {
            case 'all':
                $events = $query->latest()->get();
                break;
            case 'comments':
                $events = $query->where(['model_type' => Comments::class])->latest()->get();
                break;
            case 'events':
                $events = $query->where(['model_type' => Event::class])->latest()->get();
                break;
            case 'likes':
                $events = $query->whereIn('model_type', [Like::class, CommentVotes::class])->latest()->get();
                break;
            case 'tags':

                break;
            case 'publishing':

                break;
        }

        foreach ($events as $event) {
            $event->update(['status' => EventsLog::STATUS_READ]);
        }

        return $this->response->array(['success' => true]);
    }

    /**
     * My Account - get scrapbook
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/my-account/get-scrapbook",
     *     description="My Account - get scrapbook",
     *     operationId="api.my-account.get-scrapbook",
     *     produces={"application/json"},
     *     tags={"My Account"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="tabs",
     *     in="query",
     *     description="all || comments || events || likes || tags || publishing",
     *     default="all",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="modelId",
     *     in="query",
     *     description="ID or array list ID",
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
    public function getScrapbook(Request $request)
    {
        $user = $this->auth->user();
        $scrapbooks = ScrapBook::where(['user_id' => $user->id])->latest()->get();

        $news = $scrapbooks->where('model_type', News::class)->values()->all();

        return $this->response->array($news);
    }
}