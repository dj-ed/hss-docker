<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $sport_id
 * @property integer $gender
 * @property string $title
 * @property boolean $lock
 * @property integer $status
 * @property integer $popular
 * @property string $created_at
 * @property integer $layout
 * @property string $text
 * @property integer $author_id
 * @property integer $sort_order
 * @property boolean $is_headline
 * @property string $app_name
 * @property string $type
 * @property string $external_link
 * @property string $decline_massage
 * @property string $external_source
 * @property string $external_author
 * @property string $slug
 * @property integer $game_id
 * @property string $game_url
 * @property Sports $sport
 * @property Comments $comments
 * @property Like $likes
 * @property ScrapBook $scrapbooks
 * @property Review $reviews
 * @property NewsContributors $newsContributors
 * @property NewsLock $newsLocks
 * @property NewsMedia $media
 * @property NewsPostingHistory $newsPostingHistories
 * @property NewsSections $newsSections
 * @property NewsTags $tags
 */
class News extends Base
{
    const STATUS_PENDING = 0;
    const STATUS_IN_REVIEW = 1;
    const STATUS_APPROVED = 2;
    const STATUS_DISAPPROVED = 3;
    const STATUS_REMOVED = 4;
    const STATUS_REMOVED_COMPLETELY = 5;
    const STATUS_DRAFT = 6;

    /**
     * @var array
     */
    protected $fillable = [
        'sport_id',
        'gender',
        'title',
        'lock',
        'status',
        'popular',
        'created_at',
        'layout',
        'text',
        'author_id',
        'sort_order',
        'is_headline',
        'app_name',
        'type',
        'external_link',
        'decline_massage',
        'external_source',
        'external_author',
        'slug',
        'game_id',
        'game_url'
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
    public function sport()
    {
        return $this->belongsTo('App\Models\Sports');
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function reviews()
    {
        return $this->morphMany('App\Models\Review', 'model');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contributors()
    {
        return $this->hasMany('App\Models\NewsContributors');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locks()
    {
        return $this->hasMany('App\Models\NewsLock');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany('App\Models\NewsMedia');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function postingHistories()
    {
        return $this->hasMany('App\Models\NewsPostingHistory');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sections()
    {
        return $this->hasMany('App\Models\NewsSections');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tags()
    {
        return $this->hasMany('App\Models\NewsTags');
    }

    public function scopeActive($query)
    {
        return $query->where(['status' => static::STATUS_APPROVED]);
    }

    /**
     * scopeFilterSportGender
     *
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeFilterSportGender($query, $request)
    {
        $where = [];
        if ($request->get('sportId')) {
            $where['sport_id'] = $request->get('sportId');
        }

        if ($request->get('genderId')) {
            $where['gender'] = $request['genderId'];
        }

        if (!empty($where)) {
            return $query->where($where);
        }

        return $query;
    }

    /**
     * scopeIsHeadline
     *
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeIsHeadline($query, $request)
    {
        if ($request->get('isHeadline') == 1) {
            return $query->where(['is_headline' => $request['isHeadline']]);
        }

        return $query;
    }

    /**
     * scopeFilterSection
     *
     * @param $query
     * @param $request
     * @return mixed
     */
    public function scopeFilterSection($query, $request)
    {
        $where = [];
        $typeId = [];
        if ($request->get('section')) {
            switch ($request->get('section')) {
                case 'teams':
                    $team = Team::where(['id' => $request->get('sectionId')])->first();
                    $typeId = [$team->id];
                    break;
                case 'schools':
                    $school = School::where(['id' => $request->get('sectionId')])->first();
                    $typeId = [$school->id];
                    break;
                case 'players':
                    $player = Player::where(['id' => $request->get('sectionId')])->first();
                    $typeId = [$player->id];
                    break;
                case 'main':
                    $query->where('is_headline', 1);
                    $typeId = [1, 2, 3];
                    break;
            }

            if (!empty($typeId)) {
                $where = [
                    'type_model' => $request->get('section'),
                ];
            }
            $newsIdList = NewsSections::where($where)->whereIn('type_id', $typeId)->get()->pluck('news_id');
            return $query->whereIn('id', $newsIdList);
        }

        return $query;
    }

    /**
     * getAuthors
     *
     * @return string
     */
    public function getAuthors()
    {
        $contributors = [];
        if ($this->contributors) {
            $contributors = $this->contributors->map(function ($value) {
                return $value->name;
            })->toArray();
        }

        return (!empty($this->external_author)) ? $this->external_author : implode(', ', $contributors);
    }

    /**
     * getHeadlinesNewsIds
     *
     * @param $request
     * @return array
     */
    public static function getHeadlinesNewsIds($request)
    {
        $result = [];

        $newsArr = static::select(['id', 'lock', 'sort_order'])->active()->filterSportGender($request)
                         ->filterSection($request)->orderBy(\DB::raw('sort_order is NULL'))->orderBy('sort_order')
                         ->orderBy('created_at', 'desc')->get()->toArray();

        if (!empty($newsArr)) {
            $locked = $notLocked = [];
            $currentNotLockedKey = 1;
            foreach ($newsArr as $news) {
                if (!empty($news['lock'])) {
                    $locked[$news['sort_order']] = $news['id'];
                } else {
                    $notLocked[$currentNotLockedKey] = $news['id'];
                    $currentNotLockedKey++;
                }
            }

            $currentNotLockedKey = 1;
            $countNews = count($newsArr);

            for ($i = 0; $i <= $countNews; $i++) {
                if (empty($locked[$i + 1])) {
                    if (!empty($notLocked[$currentNotLockedKey])) {
                        $result[$i + 1] = $notLocked[$currentNotLockedKey];
                        $currentNotLockedKey++;
                    }
                } else {
                    $result[$i + 1] = $locked[$i + 1];
                    unset($locked[$i + 1]);
                }
            }
            if (!empty($locked)) {
                //add news in not checked positions
                foreach ($locked as $lock) {
                    $result[] = $lock;
                }
            }
        }

        return array_values($result);
    }

    /**
     * optionalIdHeadlines
     *
     * @param $request
     * @param $newsIds
     * @param $currentNews
     * @param $limit
     * @return array
     */
    public static function optionalIdHeadlines($request, $newsIds, $currentNews, $limit)
    {
        $idLists = [];

        if (!empty($currentNews)) {
            $index = array_search($currentNews->id, $newsIds) + 1;
            if (!empty($request->get('firstLoad'))) {
                $index -= 1;
            }
        } else {
            $index = 0;
        }

        if (!empty($newsIds) && $index !== false) {
            $idLists = $newsIds;
            if ($request->get('direction') == 'prev') {
                $index = $index - $limit - 1;
                if ($index <= 0) {
                    $limit = $index + $limit;
                    $index = 0;
                }
            }
            $idLists = array_splice($idLists, $index, $limit);
        }

        return $idLists;
    }

    /**
     * slicedHeadlinesNews
     *
     * @param $request
     * @param $newsIds
     * @param $currentNews
     * @param $limit
     * @return $this|array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function slicedHeadlinesNews($request, $newsIds, $currentNews, $limit)
    {
        $news = [];
        $ids = static::optionalIdHeadlines($request, $newsIds, $currentNews, $limit);

        if (!empty($ids)) {
            $news = News::whereIn('id', $ids)->orderBy(\DB::raw('FIELD(id,' . implode(',', $ids) . ')'));
            $news = $news->limit($limit)->get();
        }

        return $news;
    }

    /**
     * findNewsBySlag
     *
     * @param $request
     * @param $query
     * @return null|array|string
     */
    public static function findNewsBySlag($request, $query)
    {
        $currentNews = null;
        if ($request->get('newsSlug')) {
            $currentNews = $query->where(['slug' => $request->get('newsSlug')])->first();
            if (empty($currentNews)) {
                return $currentNews = 'error';
            }
        }

        return $currentNews;
    }

    /**
     * optionalHeadlines
     *
     * @param $request
     * @param $sort
     * @param $currentNews
     * @param $limit
     * @return News|array|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function optionalHeadlines($request, $sort, $currentNews, $limit)
    {
        $newsIds = null;
        if (empty($request->get('newsIds'))) {
            $newsIds = $sort->get()->pluck('id')->toArray();
        } else {
            $newsIds = explode(',', $request->get('newsIds'));
        }

        return [
            'news' => static::slicedHeadlinesNews($request, $newsIds, $currentNews, $limit),
            'id_list' => $newsIds
        ];
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
     * @param $sportId
     * @return int
     */
    public static function getSearchGlobalCount($idList, $sportId)
    {
        $query = static::whereIn('id', $idList);
        if (!empty($sportId)) {
            $query = $query->where(['sport_id' => $sportId]);
        }

        return $query->count();
    }

    /**
     * getSearchSeasonList
     *
     * @param $sportId
     * @return mixed
     */
    public static function getSearchSeasonList($sportId)
    {
        $query = static::active();
        if (!empty($sportId)) {
            $query = $query->where(['sport_id' => $sportId]);
        }

        return $query->pluck('id')->toArray();
    }
}
