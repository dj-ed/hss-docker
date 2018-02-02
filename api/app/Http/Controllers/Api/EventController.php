<?php

namespace App\Http\Controllers\Api;

use App\Models\Base;
use App\Models\Event;
use Dingo\Api\Http\Request;

/**
 * Class EventController
 *
 * @package App\Http\Controllers\Api
 */
class EventController extends ApiController
{
    /**
     * Events add - upcoming games
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/events/add-event",
     *     description="Events - upcoming games",
     *     operationId="api.events.add-event",
     *     produces={"application/json"},
     *     tags={"Events Auth User"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
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
     *     description="game",
     *     default="game",
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
    public function addEvents(Request $request)
    {
        $user = $this->auth->user();
        $modelType = Base::TYPES[$request->get('modelType')];
        if (empty($user)) {
            return $this->response->array(['success' => false]);
        }
        $result = Event::postAddEvents($user, $request, $modelType);

        return $this->response->array(['success' => $result]);
    }

    /**
     * Events - list
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/events/get-event",
     *     description="Events - list",
     *     operationId="api.events.get-event",
     *     produces={"application/json"},
     *     tags={"Events Auth User"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
     *     @SWG\Response(response=200, description="Successful operation"),
     *     @SWG\Response(response=400, description="Show error message"),
     *     @SWG\Response(response=401, description="Redirect to login page"),
     *     @SWG\Response(response=409, description="Redirect to page 404"),
     *     @SWG\Response(response=500, description="Internal server error")
     * )
     */
    public function getEvents(Request $request)
    {
        $user = $this->auth->user();
        if (empty($user)) {
            return $this->response->array(['success' => false]);
        }
        $result = Event::getEventsList($user);

        return $this->response->array($result);
    }

    /**
     * Events remove - upcoming games
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/events/remove-event",
     *     description="Events - upcoming games",
     *     operationId="api.events.remove-event",
     *     produces={"application/json"},
     *     tags={"Events Auth User"},
     *     @SWG\Parameter(
     *     name="Authorization",
     *     in="header",
     *     description="token auth",
     *     required=true,
     *     default="Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpZCI6NzksImlzcyI6Imh0dHA6XC9cL2hzcy1hcGkudjEiLCJhdWQiOiJodHRwOlwvXC9oc3MtYXBpLnYxIiwiaWF0IjoxNTEzODg5MDAyLCJleHAiOjE1NDU0MjUwMDJ9.eB-Gcx4Dkko-C7Nfql0525nfOCJlGkLsl7m-GGhCPF4",
     *     type="string"
     *     ),
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
     *     description="game",
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
    public function removeEvents(Request $request)
    {
        $user = $this->auth->user();
        $modelType = Base::TYPES[$request['modelType']];
        if (empty($user)) {
            return $this->response->array(['success' => false]);
        }
        $result = Event::postRemoveEvents($user, $request, $modelType);

        return $this->response->array(['success' => $result]);
    }
}
