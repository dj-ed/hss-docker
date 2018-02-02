<?php

namespace App\Http\Controllers\Api;

use App\Models\Base;
use App\Models\Review;
use Dingo\Api\Http\Request;

/**
 * Class ReviewController
 *
 * @package App\Http\Controllers\Api
 */
class ReviewController extends ApiController
{
    /**
     * Add reviews news from users
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/reviews/post-views",
     *     description="Add reviews news from users",
     *     operationId="api.reviews.post-views",
     *     produces={"application/json"},
     *     tags={"Reviews"},
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
     *     description="media, news, ...",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function postViews(Request $request)
    {
        $modelType = Base::TYPES[$request->get('modelType')];
        Review::postReview($request, $modelType);

        return $this->response->array(['success' => true]);
    }
}
