<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $model_id
 * @property string $model_type
 * @property integer $user_id
 * @property integer $season_id
 * @property integer $sport_id
 */
class Favorite extends Base
{
    /**
     * @var string
     */
    protected $table = 'favorites';

    /**
     * @var array
     */
    protected $fillable = ['model_id', 'model_type', 'user_id', 'season_id', 'sport_id'];

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
     * isFavorites
     *
     * @param $model
     * @param $controller
     * @return bool
     */
    public static function isFavorites($model, $controller)
    {
        $favorite = $model->favorites()->where(['user_id' => (new $controller)->auth->user()])->get();

        return ($favorite->isNotEmpty()) ? true : false;
    }

    public static function postFavorites($user, $request, $modelType)
    {
        $sportId = null;
        switch ($modelType) {
            case Player::class:
                $sportId = Player::with('team')->find($request['modelId'])->team->sport_id;
                break;
            case Team::class:
                $sportId = Team::find($request['modelId'])->sport_id;
                break;
        }

        $favoriteData = [
            'model_id' => $request['modelId'],
            'model_type' => $modelType,
            'user_id' => $user->id,
            'season_id' => $request['seasonId'],
            'sport_id' => $sportId
        ];

        $exists = static::where($favoriteData);
        if (empty($exists->count())) {
            static::create($favoriteData);
        } else {
            $deleted = $exists->first();
            $deleted->delete();
        }
    }

    /**
     * getFavoritesList
     *
     * @param $user
     * @param $seasonId
     * @return array
     */
    public static function getFavoritesList($user, $seasonId)
    {
        $favorites = static::where(['user_id' => $user->id, 'season_id' => $seasonId])->get();
        $player = $coach = $team = $school = [];
        foreach ($favorites as $favorite) {
            switch ($favorite->model_type) {
                case Player::class:
                    $player[] = ['id' => $favorite->model_id, 'user_id' => $favorite->user_id];
                    break;
//                case TeamCoach::class:
//                    $coach[] = ['id' => $favorite->model_id, 'user_id' => $favorite->user_id];
//                    break;
                case Team::class:
                    $team[] = ['id' => $favorite->model_id, 'user_id' => $favorite->user_id];
                    break;
                case School::class:
                    $school[] = ['id' => $favorite->model_id, 'user_id' => $favorite->user_id];
                    break;
            }
        }
        $data = [
            'players' => $player,
            //            'coaches' => $coach,
            'teams' => $team,
            'schools' => $school
        ];

        return $data;
    }

    public static function getFavorites($user, $seasonId)
    {
        return static::where([
            'user_id' => $user->id,
            'season_id' => $seasonId
        ])->get();
    }

    public static function getUniqueSportIdFromFavorites($favorites)
    {
        $sportsArr = $schoolId = [];
        foreach ($favorites as $favorite) {
            switch ($favorite->model_type) {
                case Player::class:
                    $sportsArr[] = $favorite->sport_id;
                    break;
                case Team::class:
                    $sportsArr[] = $favorite->sport_id;
                    break;
                case School::class:
                    $schoolId[] = $favorite->model_id;
                    break;
            }
        }

        $schools = School::with('sports')->whereIn('id', $schoolId)->get()->map(function ($item) {
            return $item->sports->pluck('sport_id')->toArray();
        })->toArray();


        foreach ($schools as $k => $v) {
            foreach ($v as $kq => $vq) {
                $sportsArr[] = $vq;
            }
        }

        $sportsIds = array_unique($sportsArr);
        $sportsId = array_values($sportsIds);

        return $sportsId;
    }
}
