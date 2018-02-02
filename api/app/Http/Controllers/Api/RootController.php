<?php

namespace App\Http\Controllers\Api;

use App\Models\Base;
use App\Models\Sports;
use App\Models\Player;
use App\Models\School;
use App\Models\Season;
use App\Models\Team;
use App\Models\TeamGender;
use App\Models\TeamType;
use Dingo\Api\Http\Request;
use \Symfony\Component\HttpKernel\Exception\ConflictHttpException;

/**
 * Class RootController
 *
 * @package App\Http\Controllers\Api
 */
class RootController extends ApiController
{
    /**
     * Root data
     *
     * @param Request $request
     * @return \Dingo\Api\Http\Response
     *
     * @SWG\Post(
     *     path="/root/current-variables",
     *     description="Root data",
     *     operationId="api.root.current-variables",
     *     produces={"application/json"},
     *     tags={"Root"},
     *     @SWG\Parameter(
     *     name="url",
     *     in="query",
     *     description="url parse",
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
    public function currentVariables(Request $request)
    {
        $url = $request->get('url');
        $url = explode(';', $url);
        $urlArray = explode('/', $url[0]);

        if (count($urlArray) > 2) {
            switch ($urlArray[1]) {
                case 'player';
                    $player = Player::where('id', intval($urlArray[2]))->first();
                    if (!empty($player)) {
                        return $this->getListVariables([
                            'season' => $player->team->season_id,
                            'gender' => $player->team->gender_id,
                            'sport' => $player->team->sport_id,
                            'state' => $request->get('stateId'),
                            'city' => $request->get('cityId'),
                        ]);
                    } else {
                        throw new ConflictHttpException('Player not Found');
                    }
                    break;
                case 'team';
                    $team = Team::where('id', intval($urlArray[2]))->first();
                    if (!empty($team)) {
                        return $this->getListVariables([
                            'season' => $team->season_id,
                            'gender' => $team->gender_id,
                            'sport' => $team->sport_id,
                            'state' => $request->get('stateId'),
                            'city' => $request->get('cityId'),
                        ]);
                    } else {
                        throw new ConflictHttpException('Team not Found');
                    }
                    break;
                case 'school';
                    $school = School::where('id', intval($urlArray[2]))->first();
                    if (!empty($school)) {
                        return $this->getListVariables([
                            'season' => $school->season_id,
                            'state' => $request->get('stateId'),
                            'city' => $request->get('cityId'),
                        ]);
                    } else {
                        throw new ConflictHttpException('School not Found');
                    }
                    break;
                case 'sport':
                case 'sport-stat':
                    if (!empty($urlArray[4])) {
                        $response = [];
                        $sport = Sports::where('title', ucfirst($urlArray[3]))->first();
                        $season = Season::where('title_short', $urlArray[4])->first();
                        $response['state'] = $request->get('stateId');
                        $response['city'] = $request->get('cityId');

                        if (!empty($season) && !empty($sport)) {
                            $response['season'] = $season->id;
                            $response['sport'] = $sport->id;

                            // Gender
                            $gender = null;
                            if (!empty($urlArray[5])) {
                                if (strtolower($urlArray[5]) == 'boys') {
                                    $response['gender'] = 1;
                                } elseif (strtolower($urlArray[5]) == 'girls') {
                                    $response['gender'] = 2;
                                } else {
                                    throw new ConflictHttpException('Gender not Found');
                                }
                            }
                            // Varsity
                            if (!empty($urlArray[6])) {
                                $varsityList = array_map(function ($v) {
                                    return $v['id'];
                                }, TeamType::teamTypeList());
                                if (in_array($urlArray[6], $varsityList)) {
                                    $response['varsity'] = $urlArray[6];

                                } else {
                                    throw new ConflictHttpException('Varsity not Found');
                                }
                            }

                            return $this->getListVariables($response);
                        } else {
                            throw new ConflictHttpException('Season or Sport not Found');
                        }
                    } else {
                        throw new ConflictHttpException('Page not Found');
                    }
                    break;
                case 'all-schools':
                case 'all-teams':
                case 'season':
                case 'map':
                    if (!empty($urlArray[2])) {
                        $season = Season::where('title_short', $urlArray[2])->first();
                        if (!empty($season)) {
                            return $this->getListVariables([
                                'season' => $season->id,
                                'state' => $request->get('stateId'),
                                'city' => $request->get('cityId'),
                            ]);
                        } else {
                            throw new ConflictHttpException('Season not Found');
                        }
                    } else {
                        throw new ConflictHttpException('Season not Found');
                    }
                    break;
                case 'search':
                    if (!empty($urlArray[3])) {
                        $season = Season::where('title_short', $urlArray[3])->first();
                        if (!empty($season)) {
                            return $this->getListVariables([
                                'season' => $season->id,
                                'state' => $request->get('stateId'),
                                'city' => $request->get('cityId'),
                            ]);
                        } else {
                            throw new ConflictHttpException('Season not Found');
                        }
                    } else {
                        throw new ConflictHttpException('Season not Found');
                    }
                    break;

            }
        }

        if (!empty($urlArray[1]) && in_array($urlArray[1], [
                'player',
                'team',
                'school',
                'sport',
                'all-schools',
                'all-teams',
                'map'
            ])) {
            throw new ConflictHttpException('Page Not Found');
        } else {
            // Other pages
            $currentSeason = Season::getLastActive();
            return $this->getListVariables([
                'season' => $currentSeason->id,
                'state' => $request->get('stateId'),
                'city' => $request->get('cityId'),
            ]);
        }

    }

    public function getListVariables($current)
    {
        $seasons = Season::orderBy('id', 'DESC')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'titleShort' => $item->title_short,
            ];
        });

        $sports = Sports::all()->map(function ($sport) {
            return [
                'id' => $sport->id,
                'title' => $sport->title,
                'logoUrl' => '/img/sports/' . strtolower($sport->title) . '.svg'
            ];
        });

        $where['season_id'] = $current['season'];
        if (!empty($current['state'])) {
            $where['state_id'] = $current['state'];
        }
        if (!empty($current['city'])) {
            $where['city_id'] = $current['city'];
        }

        $schoolList = School::where($where)->get()->map(function (School $school) {
            return [
                'id' => $school->id,
                'name' => $school->name,
                'shortName' => $school->getShortName(),
                'logoUrl' => $school->getSchoolLogo(),
                'seasonGamePts' => '???',
            ];
        });

        $genderList = TeamGender::teamGenderList();

        $varsityList = TeamType::teamTypeList();

        return $this->response->array([
            'currentSeasonId' => $current['season'],
            'currentSportId' => $current['sport'] ?? null,
            'currentGenderId' => $current['gender'] ?? null,
            'currentVarsityId' => $current['varsity'] ?? null,
            'seasonList' => $seasons,
            'sportList' => $sports,
            'genderList' => $genderList,
            'schoolList' => $schoolList,
            'varsityList' => $varsityList
        ]);
    }

}