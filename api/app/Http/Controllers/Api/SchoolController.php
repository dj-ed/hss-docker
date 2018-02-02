<?php

namespace App\Http\Controllers\Api;

use App\Models\Globals\Counties;
use App\Models\Globals\States;
use App\Models\School;
use App\Models\Search;
use App\Transformers\SchoolAllTransformer;
use App\Transformers\SchoolInfoTransformer;
use App\Transformers\SchoolTransformer;
use Dingo\Api\Http\Request;
use sngrl\SphinxSearch\SphinxSearch;
use Sphinx\SphinxClient;

/**
 * Class SchoolController
 *
 * @package App\Http\Controllers\Api
 */
class SchoolController extends ApiController
{
    /**
     * Get school by ID
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/school",
     *     description="Get school by ID",
     *     operationId="api.school",
     *     produces={"application/json"},
     *     tags={"School"},
     *     @SWG\Parameter(
     *     name="schoolId",
     *     in="query",
     *     description="school ID",
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
    public function index(Request $request)
    {
        $commonSchool = School::active()->with('city')->where(['id' => $request->get('schoolId')])->first();

        return $this->response->item($commonSchool, new SchoolTransformer);
    }

    /**
     * School Home - Sports
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/school/sports",
     *     description="School Home - Sports",
     *     operationId="api.school.sports",
     *     produces={"application/json"},
     *     tags={"School"},
     *     @SWG\Parameter(
     *     name="schoolId",
     *     in="query",
     *     description="school ID",
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
    public function sports(Request $request)
    {
        $data = School::getSchoolSports($request);

        return $this->response->array($data);
    }

    /**
     * School Info
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/school/info",
     *     description="School Info",
     *     operationId="api.school.info",
     *     produces={"application/json"},
     *     tags={"School"},
     *     @SWG\Parameter(
     *     name="schoolId",
     *     in="query",
     *     description="school ID",
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
    public function info(Request $request)
    {
        $school = School::active()->where(['id' => $request->get('schoolId')])->first();

        return $this->response->item($school, new SchoolInfoTransformer);
    }

    /**
     * List states and county from all schools page
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/all-schools/full-list",
     *     description="List states and county from all schools page",
     *     operationId="api.all-schools.full-list",
     *     produces={"application/json"},
     *     tags={"School"},
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
    public function getAllStateAndCounty(Request $request)
    {
        $schools = School::active()->where(['season_id' => $request->get('seasonId')])->get();
        $result = School::allSchoolsTree($schools);
        $data['data'] = School::generateResponseData($result['states'], $result['counties'], $schools, $result['abc']);
        $data['schoolsAlphabeticalList'] = $result['schoolsAbc'];

        return $this->response->array($data);
    }

    /**
     * School List
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/all-schools/full-list-schools",
     *     description="School List",
     *     operationId="api.all-schools.full-list-schools",
     *     produces={"application/json"},
     *     tags={"School"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="countyId",
     *     in="query",
     *     description="county ID",
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
    public function getAllSchools(Request $request)
    {
        $query = School::getAllSchoolList($request);
        $schools = $query->where(['school.county_id' => $request->get('countyId')])->get();

        return $this->response->collection($schools, new SchoolAllTransformer);
    }

    /**
     * Schools List By Char
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/all-schools/schools-by-char",
     *     description="Schools List By Char",
     *     operationId="api.all-schools.schools-by-char",
     *     produces={"application/json"},
     *     tags={"School"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="char",
     *     in="query",
     *     description="Char",
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
    public function getAllSchoolsByChar(Request $request)
    {
        $query = School::getAllSchoolList($request);
        if ($request->get('char') === '#') {
            $query = $query->whereRaw('SUBSTR(school.name, 1, 1) RLIKE "^[0-9]"');
        } else {
            $query = $query->whereRaw('SUBSTR(school.name, 1, 1) RLIKE "^' . $request->get('char') . '"');
        }
        $schools = $query->get();

        return $this->response->collection($schools, new SchoolAllTransformer);
    }

    /**
     * Search School by Name - State
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/all-schools/search-school",
     *     description="Search School by Name - State",
     *     operationId="api.all-schools.search-school",
     *     produces={"application/json"},
     *     tags={"School"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="searchText",
     *     in="query",
     *     description="search text",
     *     required=true,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="countyId",
     *     in="query",
     *     description="county ID",
     *     required=false,
     *     type="string"
     *     ),
     *     @SWG\Parameter(
     *     name="stateId",
     *     in="query",
     *     description="state ID",
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
    public function getAllSchoolsSearchSchool(Request $request)
    {
        $schools = collect([]);
        $schoolDB = (new SphinxSearch)->search($request->get('searchText'), 'schools')->setFieldWeights([
            'name' => 10,
            'full_name' => 9
        ])->setMatchMode(SphinxClient::SPH_MATCH_ANY)->query();

        if (!empty($schoolDB['matches'])) {
            $idList = array_keys($schoolDB['matches']);
            $schoolIds = School::getAllSchoolSearchList($request);
            $uniqueIds = Search::getUniqueList($idList, $schoolIds, false);
            if (!empty($uniqueIds)) {
                $schools = School::getFullDataAllSchoolSearch($request, $uniqueIds);
            }
        }
        $data['data'] = School::generateAllSchoolSearch($schools);

        return $this->response->array($data);
    }

    /**
     * Search School by Name - Global
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/all-schools/search-schools-global",
     *     description="Search School by Name - Global",
     *     operationId="api.all-schools.search-school",
     *     produces={"application/json"},
     *     tags={"School"},
     *     @SWG\Parameter(
     *     name="seasonId",
     *     in="query",
     *     description="season ID",
     *     required=true,
     *     type="integer"
     *     ),
     *     @SWG\Parameter(
     *     name="searchText",
     *     in="query",
     *     description="search text",
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
    public function getAllSchoolsSearchSchoolsGlobal(Request $request)
    {
        $schools = collect([]);
        $schoolDB = (new SphinxSearch)->search($request->get('searchText'), 'schools')->setFieldWeights([
            'name' => 10,
            'full_name' => 9
        ])->setMatchMode(SphinxClient::SPH_MATCH_ANY)->query();

        if (!empty($schoolDB['matches'])) {
            $idList = array_keys($schoolDB['matches']);
            $schoolIds = School::getAllSchoolSearchList($request);
            $uniqueIds = Search::getUniqueList($idList, $schoolIds, false);
            if (!empty($uniqueIds)) {
                $schools = School::getFullDataAllSchoolSearch($request, $uniqueIds);
            }
        }

        $result = School::allSchoolsTree($schools);
        $data['data'] = School::generateResponseData($result['states'], $result['counties'], $schools, $result['abc']);
        $data['schoolsAlphabeticalList'] = $result['schoolsAbc'];
        return $this->response->array($data);
    }
}