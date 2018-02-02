<?php

namespace App\Http\Controllers\Api;

use App\Models\Team;
use App\Models\UserContent;
use App\Models\UserContentAlbum;
use App\Transformers\GalleryViewAlbumTransformer;
use Dingo\Api\Http\Request;

/**
 * Class GalleryController
 *
 * @package App\Http\Controllers\Api
 */
class GalleryController extends ApiController
{
    /**
     * Gallery Sports Calendar
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/gallery/calendar",
     *     description="Gallery Sports Calendar",
     *     operationId="api.gallery.calendar",
     *     produces={"application/json"},
     *     tags={"Gallery"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="sportId",
     *     in="query",
     *     description="sport ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="page",
     *     in="query",
     *     description="page number",
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
    public function galleryCalendar(Request $request)
    {
        $limit = 6;
        $medias = UserContentAlbum::getCalendarAlbum($limit, $request);

        if (!empty($medias)) {
            $medias = UserContent::generatePaginateData($medias);
        }

        return $this->response->array($medias);
    }

    /**
     * Gallery list albums
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/gallery/albums",
     *     description="Gallery list albums",
     *     operationId="api.gallery.albums",
     *     produces={"application/json"},
     *     tags={"Gallery"},
     *     @SWG\Parameter(
     *     name="teamId",
     *     in="query",
     *     description="team ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="playerId",
     *     in="query",
     *     description="Player ID",
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
    public function galleryAlbums(Request $request)
    {
        $query = (new UserContentAlbum);
        $query = UserContentAlbum::getQueryAlbums($request, $query);
        $medias = $query->with('mainImage')->orderBy('game_id')->get();

        return $this->response->array(['data' => UserContent::generateData($medias)]);
    }

    /**
     * Gallery view album
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/gallery/view-album",
     *     description="Gallery view album",
     *     operationId="api.gallery.view-album",
     *     produces={"application/json"},
     *     tags={"Gallery"},
     *     @SWG\Parameter(
     *     name="albumId",
     *     in="query",
     *     description="album ID",
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
    public function galleryViewAlbum(Request $request)
    {
        $medias = UserContent::active()->where(['album_id' => $request->get('albumId')])->with('userContentPlayers')
                             ->get();

        return $this->response->collection($medias, new GalleryViewAlbumTransformer);
    }
}