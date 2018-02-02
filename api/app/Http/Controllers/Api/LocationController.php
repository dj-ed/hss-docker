<?php

namespace App\Http\Controllers\Api;

use App\Models\Globals\City;
use App\Models\Globals\States;
use App\Models\Globals\ZipCode;
use App\Models\School;
use App\Models\Team;
use App\Transformers\LocationCitiesTransformer;
use App\Transformers\LocationStatesTransformer;
use App\Transformers\LocationTeamsTransformer;
use App\Transformers\LocationZipCodeTransformer;
use Dingo\Api\Http\Request;

/**
 * Class LocationController
 *
 * @package App\Http\Controllers\Api
 */
class LocationController extends ApiController
{
    /**
     * Location states list
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/location/states",
     *     description="Location states list",
     *     operationId="api.location.states",
     *     produces={"application/json"},
     *     tags={"Location"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
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
    public function locationStates(Request $request)
    {
        $states = States::getLocationStates($request);

        return $this->response->collection($states, new LocationStatesTransformer);
    }

    /**
     * Location current city or default city by most popular school
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/location/city",
     *     description="Location current city or default city by most popular school",
     *     operationId="api.location.city",
     *     produces={"application/json"},
     *     tags={"Location"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="city ID",
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
    public function locationCity(Request $request)
    {
        $city = City::getLocationCity($request->get('cityId'), $request->get('seasonId'));

        if (empty($city)) {
            $mostPopularSchool = School::getLocationMostPopular($request->get('seasonId'), true);

            if (empty($mostPopularSchool)) {
                $mostPopularSchool = School::getLocationMostPopular($request->get('seasonId'));
            }

            $city = City::getLocationCity($mostPopularSchool->city_id, $mostPopularSchool->season_id);
        }

        return $this->response->item($city, new LocationCitiesTransformer);
    }

    /**
     * Location cities list
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/location/cities",
     *     description="Location cities list",
     *     operationId="api.location.cities",
     *     produces={"application/json"},
     *     tags={"Location"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
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
    public function locationCities(Request $request)
    {
        $cities = City::getLocationCities($request->get('seasonId'));

        return $this->response->collection($cities, new LocationCitiesTransformer);
    }

    /**
     * Location teams list
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/location/teams",
     *     description="Location teams list",
     *     operationId="api.location.teams",
     *     produces={"application/json"},
     *     tags={"Location"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="state ID",
     *     required=false,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="cityId",
     *     in="query",
     *     description="city ID",
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
    public function locationTeams(Request $request)
    {
        $teams = Team::getLocationTeams($request);

        return $this->response->collection($teams, new LocationTeamsTransformer);
    }

    /**
     * Location search from zip code
     *
     * @param \Dingo\Api\Http\Request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/location/zip-code",
     *     description="Location search from zip code",
     *     operationId="api.location.zip.code",
     *     produces={"application/json"},
     *     tags={"Location"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="zipCode",
     *     in="query",
     *     description="zip code",
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
    public function locationZipCode(Request $request)
    {
        $cityIdList = School::getLocationCityList($request->get('seasonId'));
        $zipCodes = ZipCode::getLocationByZip($request->get('zipCode'));
        $zipCodeUniques = $zipCodes->whereIn('city_id', $cityIdList)->values();

        return $this->response->collection($zipCodeUniques, new LocationZipCodeTransformer);
    }
}
