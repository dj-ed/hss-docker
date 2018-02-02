<?php

namespace App\Http\Controllers\Api;

use App\Models\News;
use App\Models\NewsMedia;
use App\Models\NewsSections;
use App\Models\NewsTags;
use App\Transformers\News\NewsListStreamTransformer;
use App\Transformers\News\NewsTransformer;
use App\Transformers\News\ShortNewsTransformer;
use App\Transformers\News\VideoNewsTransformer;
use Dingo\Api\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\DataArraySerializer;

/**
 * Class NewsController
 *
 * @package App\Http\Controllers\Api
 */
class NewsController extends ApiController
{
    /**
     * Top news slider
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/top-news",
     *     description="Top news slider",
     *     operationId="api.news.top-news",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function topNews(Request $request)
    {
        $limit = 6;
        $topNewsId = NewsSections::where([
            'type_id' => 1,
            'type_model' => 'main'
        ])->get()->pluck('news_id');

        $news = News::active()->filterSportGender($request)->whereIn('id', $topNewsId)->where([
            'is_headline' => 1,
        ])->orderBy('sort_order')->limit($limit)->get();

        return $this->response->collection($news, new NewsTransformer);
    }

    /**
     * Headlines - Latest News
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/latest-top-news",
     *     description="Headlines - Latest News",
     *     operationId="api.news.latest-top-news",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="section",
     *     in="query",
     *     description="'main' || 'sport' || 'teams' || 'schools' || 'players'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="sectionId",
     *     in="query",
     *     description="required for  'teams', 'schools', 'players'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function latestTopNews(Request $request)
    {
        $limit = 10;
        $news = News::active()->filterSportGender($request)->where([
            'is_headline' => 1
        ])->orderBy('sort_order')->limit($limit)->get();

        return $this->response->collection($news, new ShortNewsTransformer);
    }

    /**
     * Headlines - Last Video News
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/last-video-news",
     *     description="Headlines - Last Video News",
     *     operationId="api.news.last-video-news",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="section",
     *     in="query",
     *     description="'main' || 'sport' || 'teams' || 'schools' || 'players'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="sectionId",
     *     in="query",
     *     description="required for  'teams', 'schools', 'players'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function lastVideoNews(Request $request)
    {
        $newsVideoIdList = NewsMedia::whereRaw('`type` = "video" OR `type` = "iframe"')->get()->pluck('news_id');
        $news = News::active()->filterSportGender($request)->filterSection($request)->whereIn('id', $newsVideoIdList)
                    ->orderBy('created_at', 'DESC')->first();
        return $this->response->item($news, new VideoNewsTransformer);
    }

    /**
     * Headlines - Latest News
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/latest-news",
     *     description="Headlines - Latest News",
     *     operationId="api.news.latest-news",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="section",
     *     in="query",
     *     description="'main' || 'sport' || 'teams' || 'schools' || 'players'",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="sectionId",
     *     in="query",
     *     description="required for  'teams', 'schools', 'players'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function latestNews(Request $request)
    {
        $limit = 4;
        $news = News::active()->filterSportGender($request)->filterSection($request)->orderBy('sort_order')
                    ->limit($limit)->get();

        return $this->response->collection($news, new ShortNewsTransformer);
    }

    /**
     * Headlines - News List
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/news-list",
     *     description="Headlines - News List",
     *     operationId="api.news.news-list",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="section",
     *     in="query",
     *     description="'teams' || 'schools' || 'players'",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="sectionId",
     *     in="query",
     *     description="required for  'teams', 'schools', 'players'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="firstLoad",
     *     in="query",
     *     description="equal 'true' if it's first loading, otherwise always 'false'",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="direction",
     *     in="query",
     *     description="direction for  loading",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="newsSlug",
     *     in="query",
     *     description="current news slug that need to be first of the list",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function newsList(Request $request)
    {
        $limit = 5;
        if ($request->get('newsSlug')) {
            $currentNews = News::active()->where(['slug' => $request->get('newsSlug')])->first();
            if (empty($currentNews)) {
                $this->response->error('news not found', 404);
            }
        }

        $news = News::active()->filterSportGender($request)->filterSection($request)->orderBy('created_at', 'desc');
        if (!empty($request->get('firstLoad'))) {
            $compare = ($request->get('direction') == 'prev') ? '>=' : '<=';
        } else {
            $compare = ($request->get('direction') == 'prev') ? '>' : '<';
        }

        if (!empty($currentNews)) {
            $news = $news->where('created_at', $compare, $currentNews->created_at);
        }

        $news = $news->limit($limit)->get();

        return $this->response->collection($news, new NewsListStreamTransformer);
    }

    /**
     * Headlines - News List Headlines
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/news-list-headlines",
     *     description="Headlines - News List Headlines",
     *     operationId="api.news.news-list-headlines",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="section",
     *     in="query",
     *     description="'main'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="firstLoad",
     *     in="query",
     *     description="equal 'true' if it's first loading, otherwise always 'false'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="direction",
     *     in="query",
     *     description="direction for  loading",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="newsSlug",
     *     in="query",
     *     description="current news slug that need to be first of the list",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="newsIds",
     *     in="query",
     *     description="array of numbers",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function newsListHeadlines(Request $request)
    {
        $limit = 5;
        $currentNews = null;

        if ($request->get('newsSlug')) {
            $currentNews = News::active()->where(['slug' => $request->get('newsSlug')])->first();
            if (empty($currentNews)) {
                $this->response->error('news not found', 404);
            }
        }

        if (empty($request->get('newsIds'))) {
            $newsIds = News::getHeadlinesNewsIds($request);
        } else {
            $newsIds = explode(',', $request->get('newsIds'));
        }

        $newsData = News::slicedHeadlinesNews($request, $newsIds, $currentNews, $limit);
        $news = (new Manager)->createData(new Collection($newsData, new NewsListStreamTransformer))->toArray();

        return $this->response->array(['data' => $news['data'], 'newsIds' => $newsIds]);
    }

    /**
     * Get news by ID
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/news-by-id",
     *     description="Get news by ID",
     *     operationId="api.news.news-by-id",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="newsId",
     *     in="query",
     *     description="news ID",
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
    public function newsById(Request $request)
    {
        $news = News::where('id', $request->get('newsId'))->first();
        if (!empty($news)) {
            return $this->response->item($news, new NewsTransformer);
        } else {
            $this->response->error('news not found', 404);
        }
    }

    /**
     * Headlines - Most Popular News
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/most-popular",
     *     description="Headlines - Most Popular News",
     *     operationId="api.news.most-popular",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="isHeadline",
     *     in="query",
     *     description="0 or 1",
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
    public function newsMostPopular(Request $request)
    {
        $limit = 4;
        $news = News::active()->filterSportGender($request)->isHeadline($request)->orderBy('popular', 'desc')
                    ->limit($limit)->get();

        return $this->response->collection($news, new ShortNewsTransformer);
    }

    /**
     * Headlines - Hot Tags
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/hot-tags",
     *     description="Headlines - Hot Tags",
     *     operationId="api.news.hot-tags",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="isHeadline",
     *     in="query",
     *     description="0 or 1",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="section",
     *     in="query",
     *     description="'main'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="newsIds",
     *     in="query",
     *     description="array of numbers",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="tagName",
     *     in="query",
     *     description="dynamic tags name (tagType = all)",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="tagType",
     *     in="query",
     *     description="'news' or 'video' or 'popular' or 'all'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="firstLoad",
     *     in="query",
     *     description="equal 'true' if it's first loading, otherwise always 'false'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="newsSlug",
     *     in="query",
     *     description="current news slug that need to be first of the list",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="direction",
     *     in="query",
     *     description="direction for  loading",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="sectionId",
     *     in="query",
     *     description="required for  'teams', 'schools', 'players'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function newsHotTags(Request $request)
    {
        $limit = 5;
        $query = News::active()->filterSportGender($request)->filterSection($request)->isHeadline($request);
        $queryCurrent = News::active()->isHeadline($request);
        switch ($request->get('tagType')) {
            case 'video':
                $currentNews = News::findNewsBySlag($request, $queryCurrent);

                if ($currentNews == 'error') {
                    $this->response->error('news not found', 404);
                }

                $videoIdList = NewsMedia::whereRaw('`type` = "video" OR `type` = "iframe"')->get()->pluck('news_id');
                $sort = $query->whereIn('id', $videoIdList)->orderBy('created_at', 'DESC');
                break;
            case 'popular':
                $sort = $query->orderBy('popular', 'desc');
                $currentNews = News::findNewsBySlag($request, $queryCurrent);

                if ($currentNews == 'error') {
                    $this->response->error('news not found', 404);
                }
                break;
            case 'news':
                $sort = $query->orderBy('sort_order');
                $currentNews = News::findNewsBySlag($request, $queryCurrent);

                if ($currentNews == 'error') {
                    $this->response->error('news not found', 404);
                }
                break;
            case 'all':
                $currentNews = News::findNewsBySlag($request, $queryCurrent);

                if ($currentNews == 'error') {
                    $this->response->error('news not found', 404);
                }

                $tagsIdList = NewsTags::whereRaw('LOWER(title) LIKE \'%'.strtolower($request->get('tagName')).'%\'')
                                      ->get()->pluck('news_id')->toArray();
                $sort = $query->whereIn('id', $tagsIdList)->orderBy('created_at', 'DESC');
                break;
        }

        $newsData = News::optionalHeadlines($request, $sort, $currentNews, $limit);
        $news = (new Manager)->createData(new Collection($newsData['news'], new NewsListStreamTransformer))->toArray();

        return $this->response->array(['data' => $news['data'], 'newsIds' => $newsData['id_list']]);
    }

    /**
     * Headlines - get list Hot Tags
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/news/get-hot-tags",
     *     description="Headlines - get list Hot Tags",
     *     operationId="api.news.get-hot-tags",
     *     produces={"application/json"},
     *     tags={"News"},
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="1 or 2",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="genderId",
     *     in="query",
     *     description="1 or 2 or null",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="isHeadline",
     *     in="query",
     *     description="0 or 1",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="section",
     *     in="query",
     *     description="'main' or 'sport' or 'teams' or 'schools' or 'players'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="sectionId",
     *     in="query",
     *     description="required for 'teams', 'schools', 'players'",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getNewsHotTags(Request $request)
    {
        $idList = News::active()->filterSportGender($request)->filterSection($request)->isHeadline($request)->get()
                      ->pluck('id');
        $tags = NewsTags::findTopTagsByIdList($idList);

        return $this->response->array($tags);
    }
}