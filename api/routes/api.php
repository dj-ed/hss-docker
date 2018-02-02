<?php
$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group([], function ($api) {

        // Root
        $api->post('root/current-variables', 'App\Http\Controllers\Api\RootController@currentVariables');

        // Home
        $api->post('home', 'App\Http\Controllers\Api\HomeController@index');

        // Auth
        $api->post('auth/login', 'App\Http\Controllers\Api\AuthController@login');
        $api->post('auth/get-user', 'App\Http\Controllers\Api\AuthController@getUser');

        // School
        $api->post('school', 'App\Http\Controllers\Api\SchoolController@index');

        // Sport
        $api->post('all-teams/short-list', 'App\Http\Controllers\Api\TeamController@shortList');

        // Team
        $api->post('team', 'App\Http\Controllers\Api\TeamController@index');
        $api->post('team/game-recap', 'App\Http\Controllers\Api\TeamController@gameRecap');
        $api->post('team/game-recap-detail', 'App\Http\Controllers\Api\TeamController@gameRecapDetail');
        $api->post('team/coach-corner', 'App\Http\Controllers\Api\TeamController@coachCorner');
        $api->post('team/roster', 'App\Http\Controllers\Api\TeamController@teamRoster');
        $api->post('all-teams/full-list', 'App\Http\Controllers\Api\TeamController@getAllList');
        $api->post('all-teams/full-list-teams', 'App\Http\Controllers\Api\TeamController@getAllTeams');

        // Player
        $api->post('player', 'App\Http\Controllers\Api\PlayerController@index');
        $api->post('player/about', 'App\Http\Controllers\Api\PlayerController@getAbout');
        $api->post('player/about-stats', 'App\Http\Controllers\Api\PlayerController@getAboutSeasonStats');
        $api->post('player/player-standings', 'App\Http\Controllers\Api\PlayerController@getPlayerStandings');
        $api->post('player/player-standings-page', 'App\Http\Controllers\Api\PlayerController@getPlayerStandingsPage');
        $api->post('player/player-standings-search', 'App\Http\Controllers\Api\PlayerController@getPlayerStandingsSearch');

        /*
         * PARTIALS
         */

        // Social Stream
        $api->post('home/twitter', 'App\Http\Controllers\Api\HomeController@twitter');

        // Search
        $api->post('search', 'App\Http\Controllers\Api\SearchController@getIndex');
        $api->post('search/player', 'App\Http\Controllers\Api\SearchController@getPlayers');
        $api->post('search/coach', 'App\Http\Controllers\Api\SearchController@getCoaches');
        $api->post('search/school', 'App\Http\Controllers\Api\SearchController@getSchools');
        $api->post('search/team', 'App\Http\Controllers\Api\SearchController@getTeams');
        $api->post('search/news', 'App\Http\Controllers\Api\SearchController@getNews');
        $api->post('search/games', 'App\Http\Controllers\Api\SearchController@getGames');
        $api->post('search/media', 'App\Http\Controllers\Api\SearchController@getMedia');

        // News
        $api->post('news/news-by-id', 'App\Http\Controllers\Api\NewsController@newsById');
        $api->post('news/top-news', 'App\Http\Controllers\Api\NewsController@topNews');
        $api->post('news/latest-top-news', 'App\Http\Controllers\Api\NewsController@latestTopNews');
        $api->post('news/last-video-news', 'App\Http\Controllers\Api\NewsController@lastVideoNews');
        $api->post('news/latest-news', 'App\Http\Controllers\Api\NewsController@latestNews');
        $api->post('news/news-list', 'App\Http\Controllers\Api\NewsController@newsList');
        $api->post('news/news-list-headlines', 'App\Http\Controllers\Api\NewsController@newsListHeadlines');
        $api->post('news/most-popular', 'App\Http\Controllers\Api\NewsController@newsMostPopular');
        $api->post('news/hot-tags', 'App\Http\Controllers\Api\NewsController@newsHotTags');
        $api->post('news/get-hot-tags', 'App\Http\Controllers\Api\NewsController@getNewsHotTags');


        // Comments
        $api->post('comment/get-comments', 'App\Http\Controllers\Api\CommentController@getComments');
        $api->post('comment/add-like', 'App\Http\Controllers\Api\CommentController@addLike');
        $api->post('comment/remove-like', 'App\Http\Controllers\Api\CommentController@removeLike');
        $api->post('comment/report-abuse', 'App\Http\Controllers\Api\CommentController@reportAbuse');

        // School
        $api->post('school', 'App\Http\Controllers\Api\SchoolController@index');
        $api->post('school/sports', 'App\Http\Controllers\Api\SchoolController@sports');
        $api->post('school/info', 'App\Http\Controllers\Api\SchoolController@info');
        $api->post('all-schools/full-list', 'App\Http\Controllers\Api\SchoolController@getAllStateAndCounty');
        $api->post('all-schools/full-list-schools', 'App\Http\Controllers\Api\SchoolController@getAllSchools');
        $api->post('all-schools/schools-by-char', 'App\Http\Controllers\Api\SchoolController@getAllSchoolsByChar');
        $api->post('all-schools/search-school', 'App\Http\Controllers\Api\SchoolController@getAllSchoolsSearchSchool');
        $api->post('all-schools/search-schools-global', 'App\Http\Controllers\Api\SchoolController@getAllSchoolsSearchSchoolsGlobal');

        // Game
        $api->post('game/upcoming', 'App\Http\Controllers\Api\GameController@upcoming');
        $api->post('game/schedule-today', 'App\Http\Controllers\Api\GameController@scheduleToday');
        $api->post('game/schedule-calendar', 'App\Http\Controllers\Api\GameController@scheduleCalendar');
        $api->post('game/schedule-full', 'App\Http\Controllers\Api\GameController@scheduleFull');

        // Stats
        $api->post('statistics/leaderboard', 'App\Http\Controllers\Api\StatisticsController@leaderboard');
        $api->post('statistics/scoreboard', 'App\Http\Controllers\Api\StatisticsController@scoreboard');
        $api->post('statistics/sport-scoreboard', 'App\Http\Controllers\Api\StatisticsController@sportScoreboard');
        $api->post('statistics/sport-leaderboard', 'App\Http\Controllers\Api\StatisticsController@sportLeaderboard');
        $api->post('statistics/sport-team-standings', 'App\Http\Controllers\Api\StatisticsController@sportTeamStandings');

        $api->post('statistics/scoreboard-game-detail', 'App\Http\Controllers\Api\StatisticsController@scoreboardGameDetail');
        $api->post('statistics/full-board', 'App\Http\Controllers\Api\StatisticsController@fullBoard');
        $api->post('statistics/standings', 'App\Http\Controllers\Api\StatisticsController@standings');

        // Review
        $api->post('reviews/post-views', 'App\Http\Controllers\Api\ReviewController@postViews');

        // Gallery
        $api->post('gallery/calendar', 'App\Http\Controllers\Api\GalleryController@galleryCalendar');
        $api->post('gallery/albums', 'App\Http\Controllers\Api\GalleryController@galleryAlbums');
        $api->post('gallery/view-album', 'App\Http\Controllers\Api\GalleryController@galleryViewAlbum');

        //Location
        $api->post('location/states', 'App\Http\Controllers\Api\LocationController@locationStates');
        $api->post('location/city', 'App\Http\Controllers\Api\LocationController@locationCity');
        $api->post('location/cities', 'App\Http\Controllers\Api\LocationController@locationCities');
        $api->post('location/teams', 'App\Http\Controllers\Api\LocationController@locationTeams');
        $api->post('location/zip-code', 'App\Http\Controllers\Api\LocationController@locationZipCode');

        //text
        $api->post('texts', 'App\Http\Controllers\Api\TextController@index');
        $api->post('texts/page', 'App\Http\Controllers\Api\TextController@page');
    });

    $api->group(['middleware' => 'api.auth'], function ($api) {

        // My Account
        $api->post('my-account', 'App\Http\Controllers\Api\MyAccountController@index');
        $api->post('my-account/get-settings', 'App\Http\Controllers\Api\MyAccountController@getSettings');
        $api->post('my-account/get-favorites', 'App\Http\Controllers\Api\MyAccountController@getFavorites');
        $api->post('my-account/get-events-calendar', 'App\Http\Controllers\Api\MyAccountController@getEventsCalendar');
        $api->post('my-account/get-events-log', 'App\Http\Controllers\Api\MyAccountController@getEventsLog');
        $api->post('my-account/post-events-log-read', 'App\Http\Controllers\Api\MyAccountController@postEventsLogRead');
        $api->post('my-account/get-scrapbook', 'App\Http\Controllers\Api\MyAccountController@getScrapbook');

        // Comments
        $api->post('comment/post-text', 'App\Http\Controllers\Api\CommentController@postTextComment');
        $api->post('comment/post-audio', 'App\Http\Controllers\Api\CommentController@postAudioComment');

        // Likes
        $api->post('likes/post-like', 'App\Http\Controllers\Api\LikeController@postLikes');

        // Favorites
        $api->post('favorites/post-favorite', 'App\Http\Controllers\Api\FavoriteController@postFavorites');
        $api->post('favorites/get-favorite', 'App\Http\Controllers\Api\FavoriteController@getFavorites');

        // Scrap Book
        $api->post('scrapbook/post-scrapbook', 'App\Http\Controllers\Api\ScrapBookController@postScrapBook');
        $api->post('scrapbook/get-scrapbook', 'App\Http\Controllers\Api\ScrapBookController@getScrapBook');

        // Scrap Book
        $api->post('events/add-event', 'App\Http\Controllers\Api\EventController@addEvents');
        $api->post('events/get-event', 'App\Http\Controllers\Api\EventController@getEvents');
        $api->post('events/remove-event', 'App\Http\Controllers\Api\EventController@removeEvents');
    });
});
