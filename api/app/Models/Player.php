<?php

namespace App\Models;

use App\Transformers\FavoritesPlayerTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $team_id
 * @property string $number
 * @property integer $active
 * @property integer $status
 * @property integer $visible
 * @property string $tmp_data
 * @property integer $guard
 * @property boolean $is_signature
 * @property User $user
 * @property Team $team
 * @property GamePlayerStatBasketball $gamePlayerStatBasketballs
 * @property GamePlayerStatVolleyball $gamePlayerStatVolleyballs
 * @property PlayerInfo $playerInfos
 * @property PlayerMetrics $playerMetrics
 * @property PlayerWall $playerWalls
 * @property UserContentPlayers $userContentPlayers
 * @property XPlayerPositions $xPlayerPositions
 */
class Player extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'player';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'team_id',
        'number',
        'active',
        'status',
        'visible',
        'tmp_data',
        'guard',
        'is_signature'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function active()
    {
        return static::where(['active' => 1, 'visible' => 1]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function info()
    {
        return $this->hasOne('App\Models\PlayerInfo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function metrics()
    {
        return $this->hasOne('App\Models\PlayerMetrics');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function playerWalls()
    {
        return $this->hasMany('App\Models\PlayerWall');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userContentPlayers()
    {
        return $this->hasMany('App\Models\UserContentPlayers');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function xPlayerPositions()
    {
        return $this->hasMany('App\Models\XPlayerPositions');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany('App\Models\Favorite', 'model');
    }

    public function getGuard($short = false)
    {
        $guards = ($short) ? [
            '1' => '1st',
            '2' => '2nd',
            '3' => '3rd',
            '4' => '4th',
            '5' => '5th',
            '6' => '6th',
            '7' => '7th',
            '8' => '8th',
            '9' => 'FR.',
            '10' => 'SO.',
            '11' => 'JR.',
            '12' => 'SR.',
            '13' => 'PsG.'
        ] : [
            '1' => 'First Grade',
            '2' => 'Second Grade',
            '3' => 'Third Grade',
            '4' => 'Fourth Grade',
            '5' => 'Fifth Grade',
            '6' => 'Sixth Grade',
            '7' => 'Seventh Grade',
            '8' => 'Eighth Grade',
            '9' => 'Freshman',
            '10' => 'Sophomore',
            '11' => 'Junior',
            '12' => 'Senior',
            '13' => 'Post Graduate'
        ];

        return $guards[$this->guard];
    }

    /**
     * Get player positions string
     * @param $short : boolean
     * @param $arrayType : boolean
     * @return string | array
     */
    public function getPositions($short = false, $arrayType = false)
    {
        $field = ($short) ? 'short_title' : 'title';
        $positions = $this->xPlayerPositions->pluck('position_id');
        if ($positions) {
            $allPos = PlayerPositions::whereIn('id', $positions)->get()->pluck($field)->toArray();
            return ($arrayType) ? $allPos : implode(', ', $allPos);
        }

        return '';
    }

    public static function getPlayersList($request)
    {
        if (!empty($request['teamId'])) {
            $team = Team::where('id', $request['teamId'])->first();
            if (!empty($team)) {
                $team_list_id = [$team->id];
            }
        } else {
            $team_list_id = Team::getTeamListIds($request);
        }
        if (!empty($team_list_id)) {
            $ids = Player::whereIn('team_id', $team_list_id)->where(['visible' => 1])->pluck('id', 'id');
            return $ids;
        }
        if (!empty($ids[0])) {
            return $ids[0];
        }
        return null;
    }

    /**
     * @param $idList
     * @return array
     */
    public static function getPlayersMedia($idList)
    {
        $players = self::whereIn('id', $idList)->with('user')->get();

        if (!empty($players)) {
            foreach ($players as $player) {
                $data[] = [
                    'id' => $player['id'],
                    'name' => $player->user->first_name . ' ' . $player->user->last_name,
                    'number' => $player->number
                ];
            }
        }

        return (isset($data)) ? $data : [];
    }

    /**
     * @param $game
     * @return mixed
     */
    public static function getOpponentName($game)
    {
        return (!empty($game->game->opponentTeam)) ? $game->game->opponentTeam->name : $game->game->opponent_team_name;
    }

    /**
     * @param $playerId
     * @return string
     */
    public static function getPlayerList($playerId)
    {
        $userId = static::active()->find($playerId);
        $playerIds = static::active()->where(['user_id' => $userId->user_id])->pluck('id');

        return $playerIds->implode(',');
    }

    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public static function getPlayerData($request)
    {
        return static::selectRaw('team_id, team.sport_id, team.school_id, team.season_id, school.state_id')
                     ->where(['player.id' => $request->get('playerId')])
                     ->join('team', 'team.id', '=', 'player.team_id', 'left')
                     ->join('school', 'school.id', '=', 'team.school_id', 'left')->first();
    }

    /**
     * @param $page
     * @param $limit
     * @param $sportClass
     * @param $statsObj
     * @return mixed
     */
    public static function getPagePlayerStandings($page, $limit, $sportClass, $statsObj)
    {
        $pageStats = $statsObj->forPage($page, $limit);
        $playerIds = $pageStats->keys();
        return $sportClass->getPlayerStandings($playerIds->implode(','), null);
    }

    /**
     * @param $sportClass
     * @param $sport
     * @param $player
     * @param $request
     * @return mixed
     */
    public static function getInfoPlayerStanding($sportClass, $sport, $player, $request)
    {
        $query = $sportClass::selectRaw($sport['tableSport'] . '.player_id, SUM(' . $sport['tableSport'] . '.pts) pts_order')
                            ->join('player', 'player.id', '=', $sport['tableSport'] . '.player_id', 'left')
                            ->join('team', 'team.id', '=', 'player.team_id', 'left')
                            ->where(['team.season_id' => $player->season_id]);

        if ($request->get('viewType') == 'team') {
            $query = $query->whereRaw('team.id=' . $player->team_id);
        } elseif ($request->get('viewType') == 'school') {
            $query = $query->join('school', 'school.id', '=', 'team.school_id', 'left')
                           ->where('school.id', $player->school_id)->where('team.type_id', $player->team->type_id)
                           ->where('team.gender_id', $player->team->gender_id);
        } elseif ($request->get('viewType') == 'state') {
            $query = $query->join('school', 'school.id', '=', 'team.school_id', 'left')
                           ->join(env('DB_DATABASE_GLOBAL') . '.states', 'states.id', '=', 'school.state_id', 'left')
                           ->where('states.id', $player->state_id)->where('team.type_id', $player->team->type_id)
                           ->where('team.gender_id', $player->team->gender_id);
        }

        return $query->groupBy($sport['tableSport'] . '.player_id')->orderBy('pts_order', 'DESC')
                     ->pluck('pts_order', $sport['tableSport'] . '.player_id');
    }

    /**
     * getSearchResult
     *
     * @param $chunk
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getSearchResult($chunk)
    {
        return static::select(['player.*'])->whereIn('player.id', $chunk)
                     ->orderByRaw('FIELD(player.id, ' . implode(',', $chunk) . ')')->get();
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
        $query = static::leftJoin('team', 'player.team_id', '=', 'team.id');

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

        return $query->pluck('player.id')->toArray();
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
        $query = static::whereIn('player.id', $idList);

        if (!empty($seasonId) || !empty($sportId)) {
            $query = $query->leftJoin('team', 'player.team_id', '=', 'team.id');
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

    public static function getPlayersFromFavorites($ids)
    {
        $modelData = static::with('user')->with('team')->with('team.teamType')->with('team.teamGender')
                           ->with('xPlayerPositions')->with('metrics')->whereIn('id', $ids)->get();

        return (new Manager)->createData(new Collection($modelData, new FavoritesPlayerTransformer))->toArray();
    }
}
