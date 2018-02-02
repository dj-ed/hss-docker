<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $model_id
 * @property string $model_type
 * @property integer $user_id
 */
class ScrapBook extends Base
{
    /**
     * @var string
     */
    protected $table = 'scrap_books';

    /**
     * @var array
     */
    protected $fillable = ['model_id', 'model_type', 'user_id'];

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
     * isScrapBook
     *
     * @param $model
     * @param $controller
     * @return bool
     */
    public static function isScrapBook($model, $controller)
    {
        $scrapbooks = $model->scrapbooks()->where(['user_id' => (new $controller)->auth->user()])->get();

        return ($scrapbooks->isNotEmpty()) ? true : false;
    }

    /**
     * @param $user
     * @param $request
     * @param $modelType
     * @throws \Exception
     */
    public static function postScrapBook($user, $request, $modelType)
    {
        $scrapBookData = [
            'model_id' => $request['modelId'],
            'model_type' => $modelType,
            'user_id' => $user->id
        ];

        $exists = static::where($scrapBookData);
        if (empty($exists->count())) {
            static::create($scrapBookData);
        } else {
            $deleted = $exists->first();
            $deleted->delete();
        }
    }

    /**
     * getScrapbookList
     *
     * @param $user
     * @return array
     */
    public static function getScrapbookList($user)
    {
        $scrapbooks = static::where(['user_id' => $user->id])->get();
        $media = $news = $album = [];
        foreach ($scrapbooks as $scrapbook) {
            switch ($scrapbook->model_type) {
                case UserContent::class:
                    $media[] = ['id' => $scrapbook->model_id, 'user_id' => $scrapbook->user_id];
                    break;
                case News::class:
                    $news[] = ['id' => $scrapbook->model_id, 'user_id' => $scrapbook->user_id];
                    break;
                case UserContentAlbum::class:
                    $album[] = ['id' => $scrapbook->model_id, 'user_id' => $scrapbook->user_id];
                    break;
            }
        }
        $data = ['gallery' => $media, 'news' => $news, 'album' => $album];

        return $data;
    }
}
