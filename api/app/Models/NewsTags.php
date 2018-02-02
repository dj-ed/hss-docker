<?php

namespace App\Models;

use \DB;

/**
 * @property integer $id
 * @property integer $news_id
 * @property string $title
 * @property News $news
 */
class NewsTags extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['news_id', 'title'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function news()
    {
        return $this->belongsTo('App\Models\News');
    }

    /**
     * @param $idList
     * @return static
     */
    public static function findTopTagsByIdList($idList)
    {
        return DB::table('news_tags')->select(DB::raw('ANY_VALUE(`news_id`), `title`, COUNT(`id`) `count`'))
                  ->whereIn('title', function ($query) {
                      $query->select(DB::raw('t2.`title` FROM `news_tags` AS t2 GROUP BY t2.`title` HAVING COUNT(*) >= 1'));
                  })->whereIn('news_id', $idList)->groupBy('title')->orderBy('count', 'DESC')->limit(6)
                  ->get()->pluck('title');
    }
}
