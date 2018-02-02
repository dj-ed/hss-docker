<?php

namespace App\Models;

use Storage;

/**
 * @property integer $id
 * @property integer $author_id
 * @property string $source_link
 * @property string $title
 * @property string $description
 * @property string $author
 * @property string $publish_date
 * @property string $src
 * @property string $status
 * @property string $media_type
 * @property string $posted_date
 * @property boolean $visible
 * @property string $decline_message
 * @property integer $deleted
 * @property string $cropper_position
 * @property integer $album_id
 * @property User $user
 * @property UserContentPlayers $userContentPlayers
 * @property CommonComments $comments
 * @property Like $likes
 */
class UserContent extends Base
{
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_IN_REVIEW = 'in_review';
    const STATUS_DECLINED = 'declined';
    const STATUS_ARCHIVED = 'archived';
    const STATUS_VISIBLE_ACTIVE = 1;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_content';

    /**
     * @var array
     */
    protected $fillable = [
        'author_id',
        'source_link',
        'title',
        'description',
        'author',
        'publish_date',
        'src',
        'status',
        'media_type',
        'posted_date',
        'visible',
        'decline_message',
        'deleted',
        'cropper_position',
        'album_id'
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
    public function albums()
    {
        return $this->belongsTo('App\Models\UserContentAlbum');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'author_id');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userContentPlayers()
    {
        return $this->hasMany('App\Models\UserContentPlayers', 'content_id');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where([
            'status' => static::STATUS_APPROVED,
            'visible' => static::STATUS_VISIBLE_ACTIVE,
            'deleted' => null
        ]);
    }

    /**
     * getMediaSourceLink
     *
     * @param $id
     * @param $type
     * @return array
     */
    public static function getMediaSourceLink($id, $type)
    {
        $format = ($type == 'photo') ? 'jpg' : 'mp4';
        $s3 = Storage::disk('s3');
        $path = 'uploads/content/' . $id . '/';
        $original = $path . 'original.' . $format;
        $thumb = $path . 'thumb.jpg';
        $thumb_small = $path . 'thumb_small.jpg';

        return [
            'original' => $s3->url($original),
            'thumb' => $s3->url($thumb),
            'thumb_small' => $s3->url($thumb_small)
        ];
    }

    /**
     * @param $game_id
     * @return array
     */
    public static function getGame($game_id)
    {
        $game = Game::with('opponentTeam')->find($game_id);
        if (!empty($game)) {
            return [
                'gameType' => $game->getShortGameType(),
                'where' => $game->where,
                'win' => $game->win,
                'team' => static::getTeamData($game->team, $game),
                'opponentTeam' => static::getTeamData($game->opponentTeam, $game),
                'scoreTeam' => $game->score_team,
                'scoreOpponent' => $game->score_opponent,
            ];
        }

        return null;
    }

    /**
     * @param $team
     * @param $game
     * @return array
     */
    public static function getTeamData($team, $game)
    {
        if (!empty($team)) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'shortName' => Team::generateShortName($team->name),
                'logoUrl' => $team->getLogo()
            ];
        } else {
            return [
                'name' => $game->opponent_team_name,
                'shortName' => Team::generateShortName($game->opponent_team_name),
                'logoUrl' => 'default.png' // TODO: default logo
            ];
        }
    }

    public static function getLikesAndCommentsCount($albumId)
    {
        $album = static::active()->with('likes')->with('comments')->where(['album_id' => $albumId])->get();
        $likes = 0;
        $comments = 0;
        foreach ($album as $item) {
            $likes += $item->likes->count();
            $comments += $item->comments->count();
        }

        return [
            'likes' => $likes,
            'comments' => $comments
        ];
    }

    /**
     * getQueryGalleryViewAlbum
     *
     * @param $request
     * @param $query
     * @return mixed
     */
    public static function getQueryGalleryViewAlbum($request, $query)
    {
        $queryWhere = $query;

        if ((!empty($request['teamId']) && $request['teamId'] != 0) && (!empty($request['gameId']) && $request['gameId'] != 0)) {
            $queryWhere = $query->where([
                'team_id' => $request['teamId'],
                'game_id' => $request['gameId']
            ]);
        }

        if (!empty($request['teamId']) && $request['teamId'] != 0) {
            $queryWhere = $query->where(['team_id' => $request['teamId']]);
        }

        if (!empty($request['gameId']) && $request['gameId'] != 0) {
            $queryWhere = $query->where(['game_id' => $request['gameId']]);
        }

        return $queryWhere;
    }

    /**
     * getCountPhotoOrVideo
     *
     * @param $albumId
     * @param $type
     * @return mixed
     */
    public static function getCountPhotoOrVideo($albumId, $type)
    {
        return static::active()->where(['media_type' => $type])->where(['album_id' => $albumId])->count();
    }

    /**
     * getGalleryAlbums
     *
     * @return mixed
     */
    public static function getGalleryAlbums()
    {
        return static::active()->selectRaw('ANY_VALUE(id) AS id, ANY_VALUE(media_type) AS media_type, 
        ANY_VALUE(source_link) AS source_link, ANY_VALUE(game_id) AS game_id,  ANY_VALUE(posted_date) AS date');
    }

    /**
     * generateData
     *
     * @param $medias
     * @return array
     */
    public static function generateData($medias)
    {
        $data = [];
        foreach ($medias as $media) {
            $data[] = [
                'id' => $media->id,
                'title' => $media->mainImage->title,
                'descriptions' => $media->mainImage->description,
                'mediaType' => $media->mainImage->media_type,
                'date' => strtotime($media->mainImage->posted_date) * 1000,
                'isIframe' => (!empty($media->mainImage->source_link)) ? true : false,
                'mediaUrl' => (!empty($media->mainImage->source_link)) ? $media->mainImage->source_link : static::getMediaSourceLink($media->mainImage->id, $media->mainImage->media_type),
                'likesCommentsCount' => static::getLikesAndCommentsCount($media->id),
                'gameId' => ($media->game_id == null) ? '00' : $media->game_id,
                'gameData' => ($media->game_id == null) ? [] : static::getGame($media->game_id),
                'countPhoto' => static::getCountPhotoOrVideo($media->id, 'photo'),
                'countVideo' => static::getCountPhotoOrVideo($media->id, 'video')
            ];
        }

        return $data;
    }

    /**
     * generatePaginateData
     *
     * @param $medias
     * @return mixed
     */
    public static function generatePaginateData($medias)
    {
        foreach ($medias as $media) {
            $media->id = $media->id;
            $media->title = $media->mainImage->title;
            $media->descriptions = $media->mainImage->description;
            $media->mediaType = $media->mainImage->media_type;
            $media->date = strtotime($media->mainImage->posted_date) * 1000;
            $media->isIframe = (!empty($media->mainImage->source_link)) ? true : false;
            $media->mediaUrl = (!empty($media->mainImage->source_link)) ? $media->mainImage->source_link : static::getMediaSourceLink($media->mainImage->id, $media->mainImage->media_type);
            $media->likesCommentsCount = static::getLikesAndCommentsCount($media->id);
            $media->gameId = ($media->game_id == null) ? '00' : $media->game_id;
            $media->gameData = ($media->game_id == null) ? [] : static::getGame($media->game_id);
            $media->countPhoto = static::getCountPhotoOrVideo($media->id, 'photo');
            $media->countVideo = static::getCountPhotoOrVideo($media->id, 'video');

            unset($media->mainImage, $media->sport_id, $media->game_id, $media->school_id, $media->team_id);
        }

        return $medias;
    }

    /**
     * getSearchResult
     *
     * @param $chunk
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getSearchResult($chunk)
    {
        return static::whereIn('id', $chunk)->orderByRaw('FIELD(id, ' . implode(',', $chunk) . ')')->get();
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
        $query = static::whereIn('user_content.id', $idList);

        if (!empty($seasonId) || !empty($sportId)) {
            $query = $query->leftJoin('user_content_albums', 'user_content.album_id', '=', 'user_content_albums.id');
        }
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

        return $query->count();
    }

    public static function getSearchSeasonList($seasonId, $sportId, $stateId, $cityId)
    {
        $query = static::leftJoin('user_content_albums', 'user_content.album_id', '=', 'user_content_albums.id');

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

        return $query->pluck('user_content.id')->toArray();
    }

    public static function getYoutubePhoto($video)
    {
        if (preg_match('/[http|https]+:\/\/(?:www\.|)youtube\.com\/watch\?(?:.*)?v=([a-zA-Z0-9_\-]+)/i', $video, $matches) || preg_match('/(?:www\.|)youtube\.com\/embed\/([a-zA-Z0-9_\-]+)/i', $video, $matches)) {
            $image = 'http://img.youtube.com/vi/' . $matches[1] . '/0.jpg';
        } else {
            $image = false;
        }

        return $image;
    }
}
