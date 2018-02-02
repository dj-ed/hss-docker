<?php

namespace App\Http\Controllers\Api;

use App\Models\Base;
use App\Models\ScrapBook;
use Dingo\Api\Http\Request;

/**
 * Class ScrapBookController
 *
 * @package App\Http\Controllers\Api
 */
class ScrapBookController extends ApiController
{
    /**
     * Scrap Book - Media, News
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/scrapbook/post-scrapbook",
     *     description="Scrap Book - Media, News",
     *     operationId="api.scrapbook.post-scrapbook",
     *     produces={"application/json"},
     *     tags={"Scrap Book Auth User"},
     *     @SWG\Parameter(
     *     name="modelId",
     *     in="query",
     *     description="ID елемента секції",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="modelType",
     *     in="query",
     *     description="gallery, news",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     * @throws \Exception
     */
    public function postScrapBook(Request $request)
    {
        $user = $this->auth->user();
        $modelType = Base::TYPES[$request['modelType']];
        if (empty($user)) {
            return $this->response->array(['success' => false]);
        }

        ScrapBook::postScrapBook($user, $request, $modelType);

        return $this->response->array(['success' => true]);
    }

    /**
     * Scrap Book - list
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/scrapbook/get-scrapbook",
     *     description="Scrap Book - list",
     *     operationId="api.scrapbook.get-scrapbook",
     *     produces={"application/json"},
     *     tags={"Scrap Book Auth User"},
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getScrapBook(Request $request)
    {
        $user = $this->auth->user();
        if (empty($user)) {
            return $this->response->array(['success' => false]);
        }

        $list = ScrapBook::getScrapbookList($user);

        return $this->response->array($list);
    }
}
