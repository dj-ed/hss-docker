<?php

namespace App\Models;

use App\Transformers\ScheduleGameTransformer;
use Carbon\Carbon;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

/**
 * @property integer $id
 * @property integer $opponent_team_id
 * @property integer $team_id
 * @property string $date
 * @property string $date_time
 * @property string $where
 * @property boolean $district
 * @property string $opponent_team_name
 * @property string $opponent_school_name
 * @property string $recap_title
 * @property string $recap_description
 * @property string $recap_notes
 * @property boolean $win
 * @property string $status
 * @property string $tmp_data
 * @property boolean $visible
 * @property integer $edit_uid
 * @property string $media_type
 * @property integer $score_team
 * @property integer $score_opponent
 * @property string $game_address
 * @property integer $opponent_game_id
 * @property string $updated_at
 * @property string $video_embed
 * @property string $match_embed
 * @property string $tournament
 * @property Team $team
 * @property Team $opponentTeam
 * @property News $news
 * @property ExelUploads $exelUploads
 * @property GamePlayerStatBasketball $gamePlayerStatBasketballs
 * @property GamePlayerStatVolleyball $gamePlayerStatVolleyballs
 * @property GameScoreboardBasketball $gameScoreboardBasketballs
 * @property GameScoreboardVolleyball $gameScoreboardVolleyballs
 * @property GameVolleyballTeamSets $gameVolleyballTeamSets
 * @property Event $events
 */
class Game extends Base
{

    const GAME_TYPE = [
        0 => 'Non-District',
        1 => 'District',
        3 => 'Tournament',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game';

    /**
     * @var array
     */
    protected $fillable = [
        'opponent_team_id',
        'team_id',
        'date',
        'date_time',
        'where',
        'district',
        'opponent_team_name',
        'opponent_school_name',
        'recap_title',
        'recap_description',
        'recap_notes',
        'win',
        'status',
        'tmp_data',
        'visible',
        'edit_uid',
        'media_type',
        'score_team',
        'score_opponent',
        'game_address',
        'opponent_game_id',
        'updated_at',
        'video_embed',
        'match_embed',
        'tournament'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team', 'team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function opponentTeam()
    {
        return $this->belongsTo('App\Models\Team', 'opponent_team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function news()
    {
        return $this->hasOne('App\Models\News');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exelUploads()
    {
        return $this->hasMany('App\Models\ExelUploads');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gamePlayerStatBasketballs()
    {
        return $this->hasMany('App\Models\GamePlayerStatBasketball');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gamePlayerStatVolleyballs()
    {
        return $this->hasMany('App\Models\GamePlayerStatVolleyball');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gameScoreboardBasketballs()
    {
        return $this->hasMany('App\Models\GameScoreboardBasketball');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gameScoreboardVolleyballs()
    {
        return $this->hasMany('App\Models\GameScoreboardVolleyball');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gameVolleyballTeamSets()
    {
        return $this->hasMany('App\Models\GameVolleyballTeamSets');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function events()
    {
        return $this->morphMany('App\Models\Event', 'model');
    }

    public static function getUpcomingGames($request)
    {
        $type = (!empty($request['type'])) ? $request['type'] : '';
        $lastSeason = Season::getLastActive();
        $games = static::select('game.*', 'team.season_id', 'team.sport_id', 'team.school_id')
                       ->join('team', 'game.team_id', 'team.id')->where('team.season_id', $lastSeason->id)
                       ->where(\DB::raw('CONCAT(date," ",COALESCE(date_time,""))'), '>=', Carbon::now())->with('team')
                       ->with('opponentTeam');

        if ($type != 'team' && $type != 'school') {
            $games->whereRaw('IF(game.id>game.opponent_game_id || ISNULL(game.opponent_game_id), game.id, game.opponent_game_id)=game.id');

        }

        if (!empty($request['genderId'])) {
            $games->where('team.gender_id', $request['genderId']);
        }

        if ($type == 'sport') {
            $games->where('team.sport_id', $request['id']);
        } elseif ($type == 'school') {
            $games->where('team.school_id', $request['id']);

        } elseif ($type == 'team') {
            $games->where('team_id', $request['id']);
        }

        if (!empty($request['stateId']) || !empty($request['cityId'])) {
            $games->leftJoin('school', 'school.id', 'team.school_id');
        }
        if (!empty($request['stateId'])) {
            $games->where(['school.state_id' => $request['stateId']]);
        }
        if (!empty($request['cityId'])) {
            $games->where(['school.city_id' => $request['cityId']]);
        }

        return $games->orderBy(\DB::raw('CONCAT(date," ",COALESCE(date_time,""))'))->get();
    }


    public static function getDayGames($request)
    {
        $date = Carbon::parse($request['date']);
        $games = static::select('game.*', 'team.sport_id', 'team.school_id', 'opponentTeam.school_id')
                       ->where('game.date', '=', $date)->where('game.status', 'approved')
                       ->whereRaw('IF(game.id>game.opponent_game_id && !ISNULL(game.opponent_game_id), game.opponent_game_id, game.id)=game.id')
                       ->join('team', 'game.team_id', 'team.id')
                       ->leftJoin('team as opponentTeam', 'game.opponent_team_id', 'opponentTeam.id')->with('team')
                       ->with('opponentTeam');

        $games = static::otherFilters($games, $request);


        return $games->get();
    }

    public static function getDateInterval($request)
    {
        $start_date = Carbon::parse($request['startDate']);
        $end_date = Carbon::parse($request['endDate']);
        $games = static::select(['game.*', 'team.sport_id', 'team.school_id', 'opponentTeam.school_id'])
                       ->whereBetween('game.date', [$start_date, $end_date])->where('game.status', 'approved')
                       ->whereRaw('IF(game.id>game.opponent_game_id && !ISNULL(game.opponent_game_id), game.opponent_game_id, game.id)=game.id')
                       ->join('team', 'game.team_id', 'team.id')
                       ->leftJoin('team as opponentTeam', 'game.opponent_team_id', 'opponentTeam.id')->with('team')
                       ->with('opponentTeam');

        $games = static::otherFilters($games, $request);


        return $games->get();

    }


    public static function getYearGames($request)
    {
        $start_date = Carbon::parse($request['startDate']);
        $end_date = Carbon::parse($request['endDate']);

        $games = static::select([
            'game.date',
            \DB::raw('ANY_VALUE(game.id) as id, ANY_VALUE(team.sport_id) as sport_id, 
        ANY_VALUE(team.school_id) as school_id,
        ANY_VALUE(game.district) as district, 
        ANY_VALUE(opponentTeam.school_id) as opponent_school_id,count(*) as count')
        ])->whereBetween('game.date', [$start_date, $end_date])->where('game.status', 'approved')
                       ->whereRaw('IF(game.id>game.opponent_game_id && !ISNULL(game.opponent_game_id), game.opponent_game_id, game.id)=game.id')
                       ->join('team', 'game.team_id', 'team.id')
                       ->leftJoin('team as opponentTeam', 'game.opponent_team_id', 'opponentTeam.id');
        $games = static::otherFilters($games, $request);
        return $games->groupBy('date')->get();

    }

    public static function getFullTimeTable($request)
    {
        $rez = [];

        $games = static::select(['game.*', 'team.sport_id', 'team.school_id', 'opponentTeam.school_id'])
                       ->where(\DB::raw('CONCAT(date," ",COALESCE(date_time,""))'), '>=', Carbon::now())
                       ->where('game.status', 'approved')
                       ->whereRaw('IF(game.id>game.opponent_game_id && !ISNULL(game.opponent_game_id), game.opponent_game_id, game.id)=game.id')
                       ->join('team', 'game.team_id', 'team.id')
                       ->leftJoin('team as opponentTeam', 'game.opponent_team_id', 'opponentTeam.id')->with('team')
                       ->with('opponentTeam');

        $games = static::otherFilters($games, $request);

        $count = $games->count();

        if (empty($request['allGames'])) {
            $games->limit(30);
        }

        $rez['games'] = $games->orderBy(\DB::raw('CONCAT(date," ",COALESCE(date_time,""))'))->get();
        $rez['remainder'] = $count - count($rez['games']);
        return $rez;

    }

    /**
     * @param $request
     * @param $limit
     * @return array
     */
    public static function getScoreboard($request, $limit)
    {
        $rez = [];
        if (!empty($request['teamId'])) {
            $team = Team::where('id', $request['teamId'])->first();
            if (!empty($team)) {
                $team_list_id = [$team->id];
            }
        } else {
            $team_list_id = Team::getTeamListIds($request);
        }

        if (!empty($team_list_id)) {
            $games = static::active()
                           ->whereRaw('CONCAT(date," ",COALESCE(date_time,"")) < NOW() AND score_team IS NOT NULL AND score_opponent IS NOT NULL')
                           ->whereIn('team_id', $team_list_id)->with('team')->with('opponentTeam')
                           ->orderBy('date', 'desc');
            $rez = $games->paginate($limit, ['*'], 'page', $request['page']);
        }

        return $rez;
    }

    public static function getSportScoreboard($request, $limit)
    {

        $rez = [];
        $manager = new Manager();
        $teams = TeamType::teamTypeByGender($request);


        foreach ($teams as $team) {
            $parameters = $request;
            $parameters['varsityId'] = $team['teamType']['id'];
            $parameters['genderId'] = $team['gender']['id'];
            $games = $manager->createData(new Collection(static::getScoreboard($parameters, $limit)
                                                               ->items(), new ScheduleGameTransformer()))->toArray();

            $rez[] = [
                'games' => $games['data'],
                'gender' => $team['gender'],
                'teamType' => $team['teamType']
            ];
        }


        return $rez;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * @return string
     */
    public function getShortGameType()
    {
        switch ($this->district) {
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
     * @param $query
     * @param $request
     * @return mixed
     */
    public static function otherFilters($query, $request)
    {
        if (!empty($request['sportId'])) {
            $query->where('team.sport_id', $request['sportId']);
        }

        if (!empty($request['genderId'])) {
            $query->where('team.gender_id', $request['genderId']);
        }

        if (!empty($request['schoolId'])) {
            $query->where(function ($q) use ($request) {
                $q->where('team.school_id', $request['schoolId'])
                  ->orWhere('opponentTeam.school_id', $request['schoolId']);
            });

        }

        if (!empty($request['teamId'])) {
            $query->where(function ($q) use ($request) {
                $q->where('game.team_id', $request['teamId'])->orWhere('game.opponent_team_id', $request['teamId']);
            });
        }

        if (!empty($request['stateId']) || !empty($request['cityId'])) {
            $query->leftJoin('school', 'school.id', 'team.school_id');
        }
        if (!empty($request['stateId'])) {
            $query->where(['school.state_id' => $request['stateId']]);
        }
        if (!empty($request['cityId'])) {
            $query->where(['school.city_id' => $request['cityId']]);
        }

        return $query;
    }

    /**
     * @param $request
     * @return array
     */
    public static function getRecapGames($request)
    {
        $rez = [];
        $teamId = $request['teamId'];
        if (!empty($request['gameId'])) {
            $gameId = $request['gameId'];
            $rez['currentGame'] = $currentGame = static::active()->where('id', $gameId)->with('team')
                                                       ->with('opponentTeam')->first();
        } else {
            $rez['currentGame'] = $currentGame = static::active()->where('team_id', $teamId)->where(function ($q) {
                $q->whereRaw('score_team IS NOT NULL OR score_opponent IS NOT NULL')
                  ->orWhereRaw('score_team != 0 OR score_opponent != 0');
            })->orderBy('date', 'desc')->first();
        }

        if (!empty($currentGame)) {
            $curGameDatetime = $currentGame->date;
            if (!empty($currentGame->date_time)) {
                $curGameDatetime .= ' ' . $currentGame->date_time;
            }
            $rez['nextGame'] = static::active()->where('team_id', $teamId)
                                     ->where(\DB::raw('CONCAT(date," ",COALESCE(date_time,""))'), '>', $curGameDatetime)
                                     ->with('team')->with('opponentTeam')
                                     ->orderBy(\DB::raw('CONCAT(date," ",COALESCE(date_time,""))'))->first();
            $rez['prevGame'] = static::active()->where('team_id', $teamId)
                                     ->where(\DB::raw('CONCAT(date," ",COALESCE(date_time,""))'), '<', $curGameDatetime)
                                     ->with('team')->with('opponentTeam')
                                     ->orderBy(\DB::raw('CONCAT(date," ",COALESCE(date_time,""))'), 'desc')->first();
        }

        return $rez;
    }

    /**
     * @return array
     */
    public function getGameMedia()
    {
        $rez = [];
        if (!empty($this->video_embed)) {
            $rez['type'] = 'embed';
            $rez['link'] = $this->video_embed;
        } elseif ($this->media_type == 'photo') {
            $rez['type'] = 'photo';
            $version = strtotime($this->updated_at);
            $rez['link'] = \Storage::disk('s3')->url('uploads/game/' . $this->id . '.jpg') . '?v=' . $version;
        } elseif ($this->media_type == 'video') {
            $rez['type'] = 'video';
            $rez['link'] = \Storage::disk('s3')->url('uploads/game/' . $this->id . '.mp4');
        }

        return $rez;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getOpponentGame()
    {
        if (!empty($this->opponent_game_id)) {
            return self::where([
                'id' => $this->opponent_game_id,
                'status' => 'approved'
            ])->first();
        }

        return null;
    }

    /**
     * @param $game
     * @param $statClass
     * @return mixed
     */
    public static function getGameRecapLeaderboard($game, $statClass)
    {

        switch ($game->team->sport->title) {
            case 'Basketball':
                $fields = ['pts', 'ast', 'reb'];
                $rez['innerColumns'] = array_keys(GamePlayerStatBasketball::$additionalAttr);
                $rez['innerColumnsName'] = array_values(GamePlayerStatBasketball::$additionalAttr);
                break;
            case 'Volleyball':
                $rez['innerColumns'] = array_keys(GamePlayerStatVolleyball::$additionalAttr);
                $rez['innerColumnsName'] = array_values(GamePlayerStatVolleyball::$additionalAttr);
                $fields = ['attack_k', 'serve_a', 'dig'];
                break;
        }

        $mainIds = Player::getPlayersList(['teamId' => $game->team_id]);
        $rez['main'] = (new $statClass)->getPlayersLeaderBoard($mainIds, $fields, 1);
        if (!empty($game->opponent_team_id)) {
            $oponnentIds = Player::getPlayersList(['teamId' => $game->opponent_team_id]);
            $rez['opponent'] = (new $statClass)->getPlayersLeaderBoard($oponnentIds, $fields, 1);
        }

        return $rez;
    }

    /**
     * @param $request
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getFullBoard($request, $limit)
    {
        $teamIds = Team::getTeamListIds($request);

        $rez = static::select(\DB::raw('ANY_VALUE(game.team_id) team_id,COUNT(game.id) gp,SUM(win) win,
        SUM(score_team) score_team,
        ROUND(SUM(win)/COUNT(game.id)*100,0) win_pct,
        SUM(game.district=1) as d_count,SUM(game.district=3) as t_count,SUM(game.district=0) as nd_count,
        team.school_id as school_id,
        team.is_logo as is_logo,
        team.use_school_logo as use_school_logo,
        team.name as team_name,
        school.county_id as county_id,
        school.state_id as state_id,
        cities.name as city,
        counties.name as county_name,
        states.abbr as short_state_name
    
        '))->where('game.status', 'approved')->whereIn('team_id', $teamIds)->join('team', 'game.team_id', 'team.id')
                     ->join('school', 'team.school_id', 'school.id')
                     ->join(env('DB_DATABASE_GLOBAL') . '.counties', 'county_id', env('DB_DATABASE_GLOBAL') . '.counties.id')
                     ->join(env('DB_DATABASE_GLOBAL') . '.states', 'school.state_id', env('DB_DATABASE_GLOBAL') . '.states.id')
                     ->join(env('DB_DATABASE_GLOBAL') . '.cities', 'school.city_id', env('DB_DATABASE_GLOBAL') . '.cities.id')
                     ->groupBy('game.team_id')->orderBy('score_team', 'desc');

        if (empty($request['page'])) {
            $request['page'] = 1;
        }
        $rez = $rez->paginate($limit, ['*'], 'page', $request['page']);

        if (!empty($rez->items())) {
            foreach ($rez->items() as $team) {
                $team->team_logo = Team::getLogoById($team['team_id'], $team['is_logo'], $team['use_school_logo']);
            }
        }

        return $rez;
    }

    /**
     * @param $request
     * @return array
     */
    public static function getTeamStandings($request)
    {
        $rez = [];
        //for 1 Team
        if (isset($request['teamId'])) {
            $teamIds = [$request['teamId']];
        } //more
        else {
            $teamIds = Team::getTeamListIds($request);
        }
        $playerLimit = 5;

        $items = static::select(\DB::raw('ANY_VALUE(game.team_id) team_id,
        SUM(score_team) score_team,
        team.is_logo as is_logo,
        team.use_school_logo as use_school_logo,
        team.name as team_name,
        team.sport_id as sport_id,
        sports.title as sport_title,
        school.county_id as county_id,
        school.state_id as state_id,
        counties.name as county_name,
        school.district as district
    
        '))->where('game.status', 'approved')->whereIn('team_id', $teamIds)->join('team', 'game.team_id', 'team.id')
                       ->join('school', 'team.school_id', 'school.id')
                       ->join(env('DB_DATABASE_GLOBAL') . '.counties', 'county_id', env('DB_DATABASE_GLOBAL') . '.counties.id')
                       ->join('sports', 'team.sport_id', 'sports.id')->groupBy('game.team_id')
                       ->orderBy('score_team', 'desc')->limit(3)->get();


        if (!empty($items[0])) {
            $sport = $items[0]->sport_title;

            switch ($sport) {
                case 'Basketball':
                    $columns = [
                        'fg' => 'FG',
                        'fgm_pct' => 'FG (%)',
                        '3pt_pct' => '3P (%)',
                        'pf' => 'PF',
                    ];
                    break;
                case 'Volleyball':
                    $columns = [
                        'sets' => 'SP',
                        'attack_pct' => 'AT (%)',
                        'total_blk' => 'Blocks',
                        'assists' => 'Assists'
                    ];
                    break;
            }
            $rez['columns'] = array_keys($columns);
            $rez['columnsName'] = array_values($columns);
            foreach ($items as $item) {
                $item->team_logo = Team::getLogoById($item->team_id, $item->is_logo, $item->use_school_logo);
                $pIds = Player::getPlayersList(['teamId' => $item->team_id]);
                $pIds = $pIds->implode(',');

                $sportClass = 'App\Models\GamePlayerStat' . $sport;
                $item->teamTotalStats = (new $sportClass)->getTotalStandings($pIds);
                //with photo
                if (isset($request['teamId'])) {
                    $item->team_photo = Team::getPhotoById($item->team_id);
                } //with top players
                else {
                    $item->topPlayersStats = (new $sportClass)->getPlayerStandings($pIds, $playerLimit);
                }
            }
            $rez['teams'] = $items->toArray();
        }

        return $rez;
    }

    /**
     * @param $sportClass
     * @param $tableSport
     * @param $request
     * @return mixed
     */
    public static function getPlayerSeasonGamesStats($sportClass, $tableSport, $request)
    {
        $player = Player::where('id', $request->get('playerId'))->first();
        if (!empty($player)) {
            $season_id = $player->team->season_id;
            return (new $sportClass)::selectRaw($tableSport . '.*, UNIX_TIMESTAMP(game.date) times')
                                    ->where(['player_id' => $request->get('playerId')])
                                    ->join('game', 'game.id', $tableSport . '.game_id')
                                    ->join('team', 'team.id', 'game.team_id')->where(['season_id' => $season_id])
                                    ->with('game.opponentTeam')->orderBy('times', 'DESC')->get();
        }
    }

    /**
     * @param $fieldsAttr
     * @return mixed
     */
    public static function getColumnsData($fieldsAttr)
    {
        $columns['innerColumns'] = array_keys($fieldsAttr);
        $columns['innerColumnsName'] = array_values($fieldsAttr);

        return $columns;
    }

    /**
     * @param $chunk
     * @param $seasonId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function getSearchResult($chunk, $seasonId)
    {
        return static::select(['game.*', 'team.sport_id', 'team.school_id', 'opponentTeam.school_id'])
                     ->whereIn('game.id', $chunk)->where(['team.season_id' => $seasonId])
                     ->join('team', 'game.team_id', 'team.id')
                     ->leftJoin('team as opponentTeam', 'game.opponent_team_id', '=', 'opponentTeam.id')->with('team')
                     ->with('opponentTeam')->orderByRaw('FIELD(game.id, ' . implode(',', $chunk) . ')')->get();
    }

    /**
     * getSearchSeasonList
     *
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @return array
     */
    public static function getSearchSeasonList($seasonId, $sportId, $stateId, $cityId)
    {
        $query = static::leftJoin('team', 'game.team_id', '=', 'team.id');

        if (!empty($seasonId)) {
            $query = $query->where(['team.season_id' => $seasonId]);
        }
        if (!empty($sportId)) {
            $query = $query->where(['team.sport_id' => $sportId]);
        }

        if (!empty($stateId) || !empty($cityId)) {
            $query = $query->leftJoin('school', 'team.school_id', '=', 'school.id');
        }
        if (!empty($stateId)) {
            $query = $query->where(['school.state_id' => $stateId]);
        }
        if (!empty($cityId)) {
            $query = $query->where(['school.city_id' => $cityId]);
        }

        return $query->pluck('game.id')->toArray();
    }

    /**
     * getSearchGlobalCount
     *
     * @param $idList
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @return int
     */
    public static function getSearchGlobalCount($idList, $seasonId, $sportId, $stateId, $cityId)
    {
        $query = static::whereIn('game.id', $idList);

        if (!empty($seasonId) || !empty($sportId)) {
            $query = $query->leftJoin('team', 'game.team_id', '=', 'team.id');
        }
        if (!empty($seasonId)) {
            $query = $query->where(['team.season_id' => $seasonId]);
        }
        if (!empty($sportId)) {
            $query = $query->where(['team.sport_id' => $sportId]);
        }

        if (!empty($stateId) || !empty($cityId)) {
            $query = $query->leftJoin('school', 'team.school_id', '=', 'school.id');
        }
        if (!empty($stateId)) {
            $query = $query->where(['school.state_id' => $stateId]);
        }
        if (!empty($cityId)) {
            $query = $query->where(['school.city_id' => $cityId]);
        }

        return $query->count();
    }
}
