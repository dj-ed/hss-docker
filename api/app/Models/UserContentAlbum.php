<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $season_id
 * @property integer $school_id
 * @property integer $sport_id
 * @property integer $team_id
 * @property mixed $game_id
 * @property string $title
 * @property UserContent $userContents
 * @property UserContent $mainImage
 * @property Season $season
 * @property School $school
 * @property Team $team
 * @property Game $game
 * @property Comments $comments
 * @property Like $likes
 * @property ScrapBook $scrapbooks
 */
class UserContentAlbum extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_content_albums';

    /**
     * @var array
     */
    protected $fillable = [
        'season_id',
        'school_id',
        'sport_id',
        'team_id',
        'game_id',
        'title',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userContents()
    {
        return $this->hasMany('App\Models\UserContent', 'album_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function mainImage()
    {
        return $this->hasOne('App\Models\UserContent', 'album_id')->orderBy('posted_date', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany('App\Models\Comments', 'model');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function likes()
    {
        return $this->morphMany('App\Models\Like', 'model');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function scrapbooks()
    {
        return $this->morphMany('App\Models\ScrapBook', 'model');
    }

    /**
     * getQueryAlbums
     *
     * @param $request
     * @param $query
     * @return mixed
     */
    public static function getQueryAlbums($request, $query)
    {
        if (!empty($request->get('teamId')) && $request->get('teamId') != 0) {
            $query = $query->where(['team_id' => $request->get('teamId')]);
        }

        if (!empty($request->get('playerId')) && $request->get('playerId') != 0) {
            $albumId = UserContentPlayers::leftJoin('user_content', 'user_content_players.content_id', '=', 'user_content.id')
                                         ->where(['player_id' => $request->get('playerId')])->groupBy('album_id')
                                         ->pluck('album_id');
            $query = $query->whereIn('id', $albumId);
        }

        return $query;
    }

    /**
     * getCalendarAlbum
     *
     * @param $limit
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function getCalendarAlbum($limit, $request)
    {
        $query = (new UserContentAlbum);
        if (!empty($request->get('sportId'))) {
            $query = $query->where(['sport_id' => $request->get('sportId')]);
        }

        return $query->selectRaw('DISTINCT user_content_albums.*')->whereRaw('user_content_albums.game_id IS NOT NULL')
                     ->where(['user_content_albums.season_id' => $request->get('seasonId')])
                     ->rightJoin('user_content', function ($q) {
                         $q->on('user_content.album_id', '=', 'user_content_albums.id')
                           ->where(['user_content.status' => 'approved', 'user_content.visible' => 1]);
                     })->with('mainImage')->orderBy('user_content_albums.game_id')
                     ->paginate($limit, ['*'], 'page', $request->get('page'));
    }

    /**
     * getSearchResult
     *
     * @param $chunk
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getSearchResult($chunk)
    {
        return static::whereIn('id', $chunk)->with('mainImage')->orderByRaw('FIELD(id, ' . implode(',', $chunk) . ')')
                     ->get();
    }

    /**
     * getAlbumTeam
     *
     * @param $teamId
     * @return mixed|null
     */
    public static function getAlbumTeam($teamId)
    {
        $album = static::where(['team_id' => $teamId])->whereRaw('game_id IS NULL')->first();
        $id = null;
        if (!empty($album)) {
            $id = $album->id;
        }

        return $id;
    }

    public static function getSearchSeasonList($seasonId, $sportId, $stateId, $cityId)
    {
        $query = static::with('mainImage');

        if (!empty($seasonId)) {
            $query = $query->where(['user_content_albums.season_id' => $seasonId]);
        }
        if (!empty($sportId)) {
            $query = $query->where(['user_content_albums.sport_id' => $sportId]);
        }

        if (!empty($stateId) || !empty($cityId)) {
            $query = $query->leftJoin('school', 'user_content_albums.school_id', '=', 'school.id');
        }
        if (!empty($stateId)) {
            $query = $query->where(['school.state_id' => $stateId]);
        }
        if (!empty($cityId)) {
            $query = $query->where(['school.city_id' => $cityId]);
        }

        return $query->pluck('user_content_albums.id')->toArray();
    }
}
