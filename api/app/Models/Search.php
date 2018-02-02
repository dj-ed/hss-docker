<?php

namespace App\Models;

use App\Transformers\GalleryViewAlbumTransformer;
use App\Transformers\ScheduleGameTransformer;
use App\Transformers\SearchCoachTransformer;
use App\Transformers\SearchGalleryAlbumTransformer;
use App\Transformers\SearchNewsTransformer;
use App\Transformers\SearchPlayerTransformer;
use App\Transformers\SearchSchoolTransformer;
use App\Transformers\SearchTeamTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use sngrl\SphinxSearch\SphinxSearch;
use Sphinx\SphinxClient;

class Search extends Base
{
    static $limit = 9999;

    /**
     * generalSearch
     *
     * @param $querySearch
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @return array
     */
    public static function generalSearch($querySearch, $seasonId, $sportId, $stateId, $cityId)
    {
        $player = $coach = $school = $team = $news = $game = [];
        $media = 0;
        $sphinx = new SphinxSearch();

        $newsDB = $sphinx->search($querySearch, 'news')->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)
                         ->query();
        if (!empty($newsDB['matches'])) {
            $idList = array_keys($newsDB['matches']);
            $news = News::getSearchGlobalCount($idList, $sportId);
        }

        $photoDB = $sphinx->search($querySearch, 'photo')->setMatchMode(SphinxClient::SPH_MATCH_ANY)
                          ->limit(self::$limit)->query();
        $videoDB = $sphinx->search($querySearch, 'video')->setMatchMode(SphinxClient::SPH_MATCH_ANY)
                          ->limit(self::$limit)->query();
        if (!empty($photoDB['matches'])) {
            $idList = array_keys($photoDB['matches']);
            $photo = UserContent::getSearchGlobalCount($idList, $seasonId, $sportId, $stateId, $cityId);
            $media += $photo;
        }
        if (!empty($videoDB['matches'])) {
            $idList = array_keys($videoDB['matches']);
            $video = UserContent::getSearchGlobalCount($idList, $seasonId, $sportId, $stateId, $cityId);
            $media += $video;
        }

        $playerDB = $sphinx->search($querySearch, 'players')->setMatchMode(SphinxClient::SPH_MATCH_ANY)
                           ->limit(self::$limit)->query();
        if (!empty($playerDB['matches'])) {
            $idList = array_keys($playerDB['matches']);
            $player = Player::getSearchGlobalCount($idList, $seasonId, $sportId, $stateId, $cityId);
        }

        $coachDB = $sphinx->search($querySearch, 'coaches')->setMatchMode(SphinxClient::SPH_MATCH_ANY)
                          ->limit(self::$limit)->query();
        if (!empty($coachDB['matches'])) {
            $idList = array_keys($coachDB['matches']);
            $coach = TeamCoach::getSearchGlobalCount($idList, $seasonId, $sportId, $stateId, $cityId);
        }

        $schoolDB = $sphinx->search($querySearch, 'schools')->setMatchMode(SphinxClient::SPH_MATCH_ANY)
                           ->limit(self::$limit)->query();
        if (!empty($schoolDB['matches'])) {
            $idList = array_keys($schoolDB['matches']);
            $school = School::getSearchGlobalCount($idList, $seasonId, $sportId, $stateId, $cityId);
        }

        $teamDB = $sphinx->search($querySearch, 'teams')->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)
                         ->query();
        if (!empty($teamDB['matches'])) {
            $idList = array_keys($teamDB['matches']);
            $team = Team::getSearchGlobalCount($idList, $seasonId, $sportId, $stateId, $cityId);
        }

        $gameDB = $sphinx->search($querySearch, 'games')->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)
                         ->query();
        if (!empty($gameDB['matches'])) {
            $idList = array_keys($gameDB['matches']);
            $game = Game::getSearchGlobalCount($idList, $seasonId, $sportId, $stateId, $cityId);
        }

        return compact('player', 'coach', 'team', 'school', 'news', 'media', 'game');
    }

    /**
     * getCountSearch
     *
     * @param $all
     * @return mixed
     */
    public static function getCountSearch($all)
    {
        $result = [];
        foreach ($all as $type => $data) {
            if (!empty($data)) {
                switch ($type) {
                    case 'player':
                        $result['player'] = $data;
                        break;
                    case 'coach':
                        $result['coach'] = $data;
                        break;
                    case 'team':
                        $result['team'] = $data;
                        break;
                    case 'school':
                        $result['school'] = $data;
                        break;
                    case 'news':
                        $result['news'] = $data;
                        break;
                    case 'media':
                        $result['media'] = $data;
                        break;
                    case 'game':
                        $result['game'] = $data;
                        break;
                }
            }
        }
        return $result;
    }

    /**
     * getSearchUsers
     *
     * @param $querySearch
     * @param $page
     * @return array|\Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getSearchUsers($querySearch, $page)
    {
        $user = [];
        $userDB = (new SphinxSearch)->search($querySearch, 'users')->setMatchMode(SphinxClient::SPH_MATCH_ANY)
                                    ->limit(self::$limit)->query();

        if (!empty($userDB['matches'])) {
            $idList = array_keys($userDB['matches']);
            $users = User::whereIn('id', $idList)->orderByRaw('FIELD(id, ' . implode(',', $idList) . ')');
            if ($page != null) {
                $user = $users->paginate(24, ['*'], 'page', $page);
            } else {
                $user = $users->get();
            }
        }

        return $user;
    }

    /**
     * getSearchNews
     *
     * @param $querySearch
     * @param $sportId
     * @param $page
     * @return array
     */
    public static function getSearchNews($querySearch, $sportId, $page)
    {
        $news = [];
        $perPage = 8;
        $newsDB = (new SphinxSearch)->search($querySearch, 'news')->setFieldWeights([
            'title' => 10,
            'tags' => 9,
            'name' => 8,
            'first_name' => 7,
            'last_name' => 6,
            'date' => 5
        ])->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)->query();

        if (!empty($newsDB['matches'])) {
            $idList = array_keys($newsDB['matches']);
            $newsIds = News::getSearchSeasonList($sportId);

            $uniqueIds = static::getUniqueList($idList, $newsIds, false);
            $data = static::generateCustomCollection($uniqueIds, $perPage, $page);
            if (!empty($data['chunk'])) {
                $newsQuery = News::getSearchResult($data['chunk']);
                $news = (new Manager)->createData(new Collection($newsQuery, new SearchNewsTransformer))->toArray();
            }

            $news['pagination'] = static::generateCustomPagination($data['total'], $data['count'], $perPage, $page, $data['total_pages']);
        }

        return $news;
    }

    /**
     * getSearchMedia
     *
     * @param $querySearch
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @param $page
     * @param $mediaType
     * @return array
     */
    public static function getSearchMedia($querySearch, $seasonId, $sportId, $stateId, $cityId, $page, $mediaType)
    {
        $photo = $data = $video = $album = [];
        $weights = [
            'title' => 10,
            'users' => 9,
            'name' => 8,
            'team_name' => 7,
            'full_name' => 7
        ];
        $perPage = 8;
        $perPageAlbum = 4;
        $sphinx = new SphinxSearch();
        $manager = new Manager();
        $photoDB = $sphinx->search($querySearch, 'photo')->setFieldWeights($weights)
                          ->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)->query();
        $videoDB = $sphinx->search($querySearch, 'video')->setFieldWeights($weights)
                          ->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)->query();
        $albumDB = $sphinx->search($querySearch, 'album')->setFieldWeights($weights)
                          ->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)->query();

        if (!empty($photoDB['matches']) && ($mediaType == 'all' || $mediaType == 'photo')) {
            $photoLists = array_keys($photoDB['matches']);
            $photoIds = UserContent::getSearchSeasonList($seasonId, $sportId, $stateId, $cityId);

            $uniqueIds = static::getUniqueList($photoLists, $photoIds, false);
            $data = static::generateCustomCollection($uniqueIds, $perPage, $page);
            if (!empty($data['chunk'])) {
                $photos = UserContent::getSearchResult($data['chunk']);
                $photo = $manager->createData(new Collection($photos, new GalleryViewAlbumTransformer))->toArray();
            }

            $photo['countView'] = static::getCountView($data['total'], $perPage);
            $photo['pagination'] = static::generateCustomPagination($data['total'], $data['count'], $perPage, $page, $data['total_pages']);
        }

        if (!empty($videoDB['matches']) && ($mediaType == 'all' || $mediaType == 'video')) {
            $videoLists = array_keys($videoDB['matches']);
            $videoIds = UserContent::getSearchSeasonList($seasonId, $sportId, $stateId, $cityId);

            $uniqueIds = static::getUniqueList($videoLists, $videoIds, false);
            $data = static::generateCustomCollection($uniqueIds, $perPage, $page);
            if (!empty($data['chunk'])) {
                $videos = UserContent::getSearchResult($data['chunk']);
                $video = $manager->createData(new Collection($videos, new GalleryViewAlbumTransformer))->toArray();
            }

            $video['countView'] = static::getCountView($data['total'], $perPage);
            $video['pagination'] = static::generateCustomPagination($data['total'], $data['count'], $perPage, $page, $data['total_pages']);
        }

        if (!empty($albumDB['matches']) && ($mediaType == 'all' || $mediaType == 'album')) {
            $albumLists = array_keys($albumDB['matches']);
            $albumIds = UserContentAlbum::getSearchSeasonList($seasonId, $sportId, $stateId, $cityId);

            $uniqueIds = static::getUniqueList($albumLists, $albumIds, false);
            $data = static::generateCustomCollection($uniqueIds, $perPageAlbum, $page);
            if (!empty($data['chunk'])) {
                $albums = UserContentAlbum::getSearchResult($data['chunk']);
                $album = $manager->createData(new Collection($albums, new SearchGalleryAlbumTransformer))->toArray();
            }

            $album['countView'] = static::getCountView($data['total'], $perPageAlbum);
            $album['pagination'] = static::generateCustomPagination($data['total'], $data['count'], $perPageAlbum, $page, $data['total_pages']);
        }

        return compact('album', 'photo', 'video');
    }

    /**
     * getSearchPlayers
     *
     * @param $querySearch
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @param $page
     * @return array
     */
    public static function getSearchPlayers($querySearch, $seasonId, $sportId, $stateId, $cityId, $page)
    {
        $player = [];
        $perPage = 12;
        $playerDB = (new SphinxSearch)->search($querySearch, 'players')->setFieldWeights([
            'name' => 10,
            'first_name' => 9,
            'last_name' => 8,
            'team_name' => 7,
            'number' => 7
        ])->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)->query();

        if (!empty($playerDB['matches'])) {
            $idList = array_keys($playerDB['matches']);
            $playerIds = Player::getSearchSeasonList($seasonId, $sportId, $stateId, $cityId);

            $uniqueIds = static::getUniqueList($idList, $playerIds, false);
            $data = static::generateCustomCollection($uniqueIds, $perPage, $page);
            if (!empty($data['chunk'])) {
                $players = Player::getSearchResult($data['chunk']);
                $player = (new Manager)->createData(new Collection($players, new SearchPlayerTransformer))->toArray();
            }

            $player['pagination'] = static::generateCustomPagination($data['total'], $data['count'], $perPage, $page, $data['total_pages']);
        }

        return $player;
    }

    /**
     * getSearchCoaches
     *
     * @param $querySearch
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @param $page
     * @return array
     */
    public static function getSearchCoaches($querySearch, $seasonId, $sportId, $stateId, $cityId, $page)
    {
        $coach = [];
        $perPage = 12;
        $coachDB = (new SphinxSearch)->search($querySearch, 'coaches')->setFieldWeights([
            'name' => 10,
            'first_name' => 9,
            'last_name' => 8,
            'teams_name' => 7
        ])->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)->query();

        if (!empty($coachDB['matches'])) {
            $idList = array_keys($coachDB['matches']);
            $coachIds = TeamCoach::getSearchSeasonList($seasonId, $sportId, $stateId, $cityId);

            $uniqueIds = static::getUniqueList($idList, $coachIds, false);
            $data = static::generateCustomCollection($uniqueIds, $perPage, $page);
            if (!empty($data['chunk'])) {
                $coaches = TeamCoach::getSearchResult($data['chunk'], $seasonId);
                $coach = (new Manager)->createData(new Collection($coaches, new SearchCoachTransformer))->toArray();
            }

            $coach['pagination'] = static::generateCustomPagination($data['total'], $data['count'], $perPage, $page, $data['total_pages']);
        }

        return $coach;
    }

    /**
     * getSearchSchools
     *
     * @param $querySearch
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @param $page
     * @return array
     */
    public static function getSearchSchools($querySearch, $seasonId, $sportId, $stateId, $cityId, $page)
    {
        $school = [];
        $perPage = 12;
        $schoolDB = (new SphinxSearch)->search($querySearch, 'schools')->setFieldWeights([
            'name' => 10,
            'full_name' => 9
        ])->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)->query();

        if (!empty($schoolDB['matches'])) {
            $idList = array_keys($schoolDB['matches']);
            $schoolIds = School::getSearchSeasonList($seasonId, $sportId, $stateId, $cityId);

            $uniqueIds = static::getUniqueList($idList, $schoolIds, false);
            $data = static::generateCustomCollection($uniqueIds, $perPage, $page);
            if (!empty($data['chunk'])) {
                $schools = School::getSearchResult($data['chunk'], $seasonId);
                $school = (new Manager)->createData(new Collection($schools, new SearchSchoolTransformer))->toArray();
            }

            $school['pagination'] = static::generateCustomPagination($data['total'], $data['count'], $perPage, $page, $data['total_pages']);
        }

        return $school;
    }

    /**
     * getSearchTeams
     *
     * @param $querySearch
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @param $page
     * @return array
     */
    public static function getSearchTeams($querySearch, $seasonId, $sportId, $stateId, $cityId, $page)
    {
        $team = [];
        $perPage = 12;
        $teamDB = (new SphinxSearch)->search($querySearch, 'teams')->setFieldWeights([
            'team_name' => 10,
            'name' => 9,
            'full_name' => 8
        ])->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)->query();

        if (!empty($teamDB['matches'])) {
            $idList = array_keys($teamDB['matches']);
            $teamIds = Team::getSearchSeasonList($seasonId, $sportId, $stateId, $cityId);

            $uniqueIds = static::getUniqueList($idList, $teamIds, false);
            $data = static::generateCustomCollection($uniqueIds, $perPage, $page);
            if (!empty($data['chunk'])) {
                $teams = Team::getSearchResult($data['chunk'], $seasonId);
                $team = (new Manager)->createData(new Collection($teams, new SearchTeamTransformer))->toArray();
            }

            $team['pagination'] = static::generateCustomPagination($data['total'], $data['count'], $perPage, $page, $data['total_pages']);
        }

        return $team;
    }

    /**
     * getSearchGame
     *
     * @param $querySearch
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @param $page
     * @return array
     */
    public static function getSearchGame($querySearch, $seasonId, $sportId, $stateId, $cityId, $page)
    {
        $querySearch = str_replace(['@', 'vs'], ['away', 'home'], $querySearch);
        $game = [];
        $perPage = 10;
        $gameDB = (new SphinxSearch)->search($querySearch, 'games')->setFieldWeights([
            'team_name' => 10,
            'team_short_name' => 10,
            'opponent_name' => 9,
            'opponent_team_name' => 9,
            'opponent_short_name' => 9,
            'date' => 8,
            'where' => 8,
            'game_type' => 7
        ])->setMatchMode(SphinxClient::SPH_MATCH_ANY)->limit(self::$limit)->query();

        if (!empty($gameDB['matches'])) {
            $idList = array_keys($gameDB['matches']);
            $gameIds = Game::getSearchSeasonList($seasonId, $sportId, $stateId, $cityId);

            $uniqueIds = static::getUniqueList($idList, $gameIds, false);
            $data = static::generateCustomCollection($uniqueIds, $perPage, $page);
            if (!empty($data['chunk'])) {
                $games = Game::getSearchResult($data['chunk'], $seasonId);
                $game = (new Manager)->createData(new Collection($games, new ScheduleGameTransformer))->toArray();
            }

            $game['pagination'] = static::generateCustomPagination($data['total'], $data['count'], $perPage, $page, $data['total_pages']);
        }

        return $game;
    }

    /**
     * getAlbumsList
     *
     * @param $collection
     * @return array
     */
    public static function getAlbumsList($collection)
    {
        $data = [];
        $albumArr = $collection->unique('album_id')->values()->all();
        foreach ($albumArr as $item) {
            $data[] = $item->album_id;
        }

        return $data;
    }

    /**
     * generateCustomPagination
     *
     * @param $total
     * @param $count
     * @param $perPage
     * @param $page
     * @param $total_pages
     * @return array
     */
    public static function generateCustomPagination($total, $count, $perPage, $page, $total_pages)
    {
        return [
            'total' => $total,
            'count' => $count,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => $total_pages,
        ];
    }

    /**
     * generateCustomCollection
     *
     * @param $idList
     * @param $perPage
     * @param $page
     * @return array
     */
    public static function generateCustomCollection($idList, $perPage, $page)
    {
        $collection = collect($idList);
        $chunk = $collection->forPage($page, $perPage)->values()->all();

        $total = count($idList);
        $count = count($chunk);
        $total_pages = ($total > 0) ? ceil($total / $perPage) : 0;

        return [
            'chunk' => $chunk,
            'total' => $total,
            'count' => $count,
            'total_pages' => $total_pages,
        ];
    }

    /**
     * getUniqueList
     *
     * @param $arrOne
     * @param $arrTwo
     * @param bool $first
     * @return array
     */
    public static function getUniqueList($arrOne, $arrTwo, $first = true)
    {
        if ($first) {
            $arrMerge = array_merge($arrOne, $arrTwo);
            $arrUnique = array_unique($arrMerge);
        } else {
            $arrUnique = array_intersect($arrOne, $arrTwo);
        }

        return array_values($arrUnique);
    }

    /**
     * getCountView
     *
     * @param $total
     * @param $perPage
     * @return null
     */
    public static function getCountView($total, $perPage)
    {
        return ($total > $perPage) ? $total - $perPage : null;
    }
}
