<?php

namespace App\Models;

use App\Transformers\EventsCalendarTransformer;
use App\Transformers\EventsCalendarYearTransformer;
use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

/**
 * @property integer $id
 * @property integer $model_id
 * @property integer $user_id
 * @property integer $status
 * @property integer $sport_id
 * @property string $model_type
 * @property Game $game
 * @property Sports $sport
 */
class Event extends Base
{
    const STATUS_WAITING = 1;
    const STATUS_APPROVED = 2;

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var array
     */
    protected $fillable = ['model_id', 'model_type', 'user_id', 'sport_id', 'status'];

    /**
     * @var array
     */
    protected $dates = ['created_at'];

    /**
     * @var string
     */
    protected $updated_at = '';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game', 'model_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo('App\Models\Sports', 'sport_id');
    }

    public static function getShortGameType($district)
    {
        switch ($district) {
            case 1:
                $str = 'D';
                break;
            case 3:
                $str = 'T';
                break;
            default:
                $str = 'ND';
        }

        return $str;
    }

    /**
     * postAddEvents
     *
     * @param $user
     * @param $request
     * @param $modelType
     * @return bool
     */
    public static function postAddEvents($user, $request, $modelType)
    {
        $sportId = null;
        switch ($modelType) {
            case Game::class:
                $sportId = Game::with('team')->find($request['modelId'])->team->sport_id;
                break;
        }

        $eventData = [
            'model_id' => $request['modelId'],
            'model_type' => $modelType,
            'user_id' => $user->id,
            'sport_id' => $sportId,
            'status' => static::STATUS_WAITING
        ];

        $exists = static::where($eventData);
        if (empty($exists->count())) {
            static::create($eventData);

            return true;
        } else {
            return false;
        }
    }

    /**
     * postRemoveEvents
     *
     * @param $user
     * @param $request
     * @param $modelType
     * @return bool
     */
    public static function postRemoveEvents($user, $request, $modelType)
    {
        $eventData = [
            'model_id' => $request['modelId'],
            'model_type' => $modelType,
            'user_id' => $user->id
        ];

        $exists = static::where($eventData);
        if (!empty($exists->count())) {
            $deleted = $exists->first();
            $deleted->delete();

            return true;
        } else {
            return false;
        }
    }

    /**
     * getEventsList
     *
     * @param $user
     * @return array
     */
    public static function getEventsList($user)
    {
        $events = static::where(['user_id' => $user->id])->get();
        $game = [];
        foreach ($events as $event) {
            $game[] = ['id' => $event->model_id, 'user_id' => $event->user_id];
        }

        return $game;
    }

    public static function getSportIdList($modelType, $userId)
    {
        return static::where(['model_type' => $modelType, 'user_id' => $userId])->get()->unique('sport_id')
                     ->pluck('sport_id');
    }

    public static function getUpcomingEvent($modelType, $userId, $sportId)
    {
        $upcoming = static::where(['events.model_type' => $modelType, 'events.user_id' => $userId])
                          ->leftJoin('game', 'game.id', 'events.model_id')->join('team', 'game.team_id', 'team.id')
                          ->where(\DB::raw('CONCAT(game.date," ",COALESCE(game.date_time,""))'), '>=', Carbon::now())
                          ->with('game')->with('game.team')->with('game.opponentTeam')
                          ->whereRaw('IF(game.id>game.opponent_game_id || ISNULL(game.opponent_game_id), game.id, game.opponent_game_id)=game.id')
                          ->orderBy(\DB::raw('CONCAT(game.date," ",COALESCE(game.date_time,""))'));

        if (!empty($sportId) && isset($sportId)) {
            $upcoming->where(['events.sport_id' => $sportId]);
        }

        return $upcoming->first();
    }

    public static function getEventsCalendar($calendarType, $modelType, $userId, $sportId, $start_date, $end_date)
    {
        $events = [];
        $transformer = null;
        $query = static::where(['model_type' => $modelType, 'user_id' => $userId])
                       ->leftJoin('game', 'game.id', 'events.model_id')->where('game.status', 'approved')
                       ->whereRaw('IF(game.id>game.opponent_game_id && !ISNULL(game.opponent_game_id), game.opponent_game_id, game.id)=game.id')
                       ->join('team', 'game.team_id', 'team.id')
                       ->join('team as opponentTeam', 'game.opponent_team_id', 'opponentTeam.id')
                       ->whereBetween('game.date', [$start_date, $end_date]);

        if (!empty($sportId)) {
            $query = $query->where(['events.sport_id' => $sportId]);
        }

        switch ($calendarType) {
            case 'week':
            case 'month':
                $events = $query->with('game')->with('game.team')->with('game.opponentTeam')->get();

                $transformer = new EventsCalendarTransformer();
                break;
            case 'year':
                $events = $query->select([
                    'game.date',
                    \DB::raw('ANY_VALUE(game.id) as id, ANY_VALUE(team.sport_id) as sport_id, 
                                  ANY_VALUE(team.school_id) as school_id, ANY_VALUE(game.district) as district, 
                                  ANY_VALUE(opponentTeam.school_id) as opponent_school_id, count(*) as count')
                ])->groupBy('game.date')->get();

                $transformer = new EventsCalendarYearTransformer();
                break;
        }

        return (new Manager)->createData(new Collection($events, $transformer))->toArray();
    }
}
