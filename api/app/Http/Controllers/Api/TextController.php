<?php

namespace App\Http\Controllers\Api;

use App\Models\Texts;
use App\Transformers\TextShortTransformer;
use App\Transformers\TextTransformer;
use Dingo\Api\Http\Request;

/**
 * Class TextController
 *
 * @package App\Http\Controllers\Api
 */
class TextController extends ApiController
{
    /**
     * Texts
     *
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/texts",
     *     description="Text list",
     *     operationId="api.texts",
     *     produces={"application/json"},
     *     tags={"Text pages"},
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function index()
    {
        $texts = Texts::all();

        return $this->response->collection($texts, new TextShortTransformer);
    }

    /**
     * Texts page
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/texts/page",
     *     description="Text page",
     *     operationId="api.texts.page",
     *     produces={"application/json"},
     *     tags={"Text pages"},
     *     @SWG\Parameter(
     *     name="url",
     *     in="query",
     *     description="url",
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
    public function page(Request $request)
    {
        $text = Texts::where(['url' => $request->get('url')])->first();
        if (empty($text)) {
            $this->response->error('page not found', 404);
        }

        return $this->response->item($text, new TextTransformer);
    }
}
