<?php

namespace App\Models;

use App\Models\Globals\Counties;
use App\Models\Globals\States;
use App\Transformers\SchoolAllTransformer;
use League\Fractal\Resource\Collection;
use \Storage;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

/**
 * @property integer $id
 * @property integer $season_id
 * @property integer $general_id
 * @property string $name
 * @property string $full_name
 * @property string $address
 * @property string $address2
 * @property string $city_id
 * @property integer $state_id
 * @property string $zip
 * @property integer $county_id
 * @property string $phone
 * @property string $phone_ext
 * @property string $fax
 * @property string $fax_ext
 * @property string $district
 * @property string $main_color
 * @property string $second_color
 * @property integer $status
 * @property integer $description
 * @property integer $type_id
 * @property integer $visible
 * @property integer $active
 * @property integer $obligation_confirmed
 * @property string $tmp_data
 * @property string $last_update
 * @property integer $edit_uid
 * @property integer $team_added
 * @property boolean $coaches_added
 * @property integer $status_global
 * @property integer $game_added
 * @property string $school_url
 * @property integer $is_logo
 * @property string $mascot_text
 * @property string $created_at
 * @property Season $season
 * @property SchoolGeneral $schoolGeneral
 * @property CoachesCorner $coachesCorners
 * @property Obligation $obligations
 * @property OtherPersonInfo $otherPersonInfos
 * @property SchoolIntegrateKrossover $schoolIntegrateKrossovers
 * @property SchoolPerson $people
 * @property SchoolSocials $socials
 * @property SchoolSport $sports
 * @property Team $teams
 * @property TeamCoach $teamCoaches
 * @property UserContent $userContents
 */
class School extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school';

    /**
     * @var array
     */
    protected $fillable = [
        'season_id',
        'general_id',
        'name',
        'full_name',
        'address',
        'address2',
        'city_id',
        'state_id',
        'zip',
        'county_id',
        'phone',
        'phone_ext',
        'fax',
        'fax_ext',
        'district',
        'main_color',
        'second_color',
        'status',
        'type_id',
        'visible',
        'active',
        'obligation_confirmed',
        'tmp_data',
        'last_update',
        'edit_uid',
        'team_added',
        'coaches_added',
        'status_global',
        'game_added',
        'school_url',
        'is_logo',
        'mascot_text',
        'description',
        'created_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolGeneral()
    {
        return $this->belongsTo('App\Models\SchoolGeneral');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coachesCorners()
    {
        return $this->hasMany('App\Models\CoachesCorner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function obligations()
    {
        return $this->hasMany('App\Models\Obligation');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function otherPersonInfos()
    {
        return $this->hasMany('App\Models\OtherPersonInfo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function integrateKrossovers()
    {
        return $this->hasMany('App\Models\SchoolIntegrateKrossover');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function people()
    {
        return $this->hasMany('App\Models\SchoolPerson');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socials()
    {
        return $this->hasOne('App\Models\SchoolSocials');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function county()
    {
        return $this->hasOne('App\Models\Globals\Counties', 'id', 'county_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function state()
    {
        return $this->hasOne('App\Models\Globals\States', 'id', 'state_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function city()
    {
        return $this->hasOne('App\Models\Globals\City', 'id', 'city_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function sports()
    {
        return $this->hasMany('App\Models\SchoolSport');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function teamCoaches()
    {
        return $this->hasMany('App\Models\TeamCoach');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userContents()
    {
        return $this->hasMany('App\Models\UserContent');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany('App\Models\Favorite', 'model');
    }

    /**
     * @return $this
     */
    public static function active()
    {
        return static::where(['active' => 1, 'visible' => 1]);
    }

    /**
     * @return string
     */
    public function getSchoolLogo()
    {
        if ($this->is_logo) {
            $schoolLogoUrl = 'uploads/schools/' . $this->id . '_logo.png';
            return Storage::disk('s3')->url($schoolLogoUrl);
        }

        return '';
    }

    /**
     * @param $isLogo
     * @param $id
     * @return string
     */
    public static function getSchoolLogoById($isLogo, $id)
    {
        if ($isLogo) {
            $schoolLogoUrl = 'uploads/schools/' . $id . '_logo.png';
            return Storage::disk('s3')->url($schoolLogoUrl);
        }

        return '';
    }

    /**
     * getShortName
     *
     * @return string
     */
    public function getShortName()
    {
        return static::transformShortName($this->name);
    }

    /**
     * getShortNameStatic
     *
     * @param $name
     * @return string
     */
    public static function getShortNameStatic($name)
    {
        return static::transformShortName($name);
    }

    /**
     * transformShortName
     *
     * @param $name
     * @return string
     */
    public static function transformShortName($name)
    {
        $nameArray = explode(' ', $name);
        if (count($nameArray) == 1) {
            $abbr = substr($nameArray[0], 0, 3);
        } elseif (count($nameArray) == 2) {
            $abbr = substr($nameArray[0], 0, 1) . substr($nameArray[1], 0, 2);
        } else {
            $abbr = substr($nameArray[0], 0, 1) . substr($nameArray[1], 0, 1) . substr($nameArray[2], 0, 1);
        }

        return strtoupper($abbr);
    }

    /**
     * getPoweredBy
     *
     * @param $schoolId
     * @return bool
     */
    public static function getPoweredBy($schoolId)
    {
        if (!empty($schoolId)) {
            $school = static::active()->find($schoolId);
            return (!empty($school->socials) && !empty($school->socials->kross_show));
        }

        return false;
    }

    /**
     * getSearchResult
     *
     * @param $chunk
     * @param $seasonId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getSearchResult($chunk, $seasonId)
    {
        return static::with('sports')->whereIn('id', $chunk)->where(['season_id' => $seasonId])
                     ->orderByRaw('FIELD(id, ' . implode(',', $chunk) . ')')->get();
    }

    /**
     * getSearchSeasonList
     *
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @return array
     */
    public static function getSearchSeasonList($seasonId, $sportId, $stateId, $cityId)
    {
        $query = static::where(['school.season_id' => $seasonId]);

        if (!empty($stateId)) {
            $query = $query->where(['school.state_id' => $stateId]);
        }
        if (!empty($cityId)) {
            $query = $query->where(['school.city_id' => $cityId]);
        }
        if (!empty($sportId)) {
            $query = $query->leftJoin('school_sport', 'school.id', '=', 'school_sport.school_id')
                           ->where(['school_sport.sport_id' => $sportId]);
        }

        return $query->pluck('school.id')->toArray();
    }

    /**
     * generateResponseData
     *
     * @param $states
     * @param $counties
     * @param $schools
     * @param $abc
     * @return array
     */
    public static function generateResponseData($states, $counties, $schools, $abc)
    {
        $data = [];
        foreach ($states as $state) {
            $statesData = [
                'stateId' => $state->id,
                'stateName' => $state->name,
                'stateShortName' => $state->abbr,
                'stateLogo' => States::getFlagLogo($state->name),
            ];

            $countiesData = [];
            $countAllSchools = 0;
            foreach ($counties as $county) {
                if ($state->id == $county->state_id) {
                    $schoolsData = [];
                    foreach ($schools as $school) {
                        if ($county->id == $school->county_id) {
                            $schoolsData[] = ['id' => $school->id];
                        }
                    }

                    $count = count($schoolsData);
                    $countiesData[] = [
                        'countyId' => $county->id,
                        'countyName' => $county->name,
                        'countyShortName' => Counties::getShortName($county->name),
                        'chars' => $abc[$county->id],
                        'count_schools' => $count
                    ];

                    $countAllSchools += $count;
                }
            }

            $statesData['county'] = $countiesData;
            $statesData['count_all_schools'] = $countAllSchools;

            $data[] = $statesData;
        }

        return $data;
    }

    /**
     * getSearchGlobalCount
     *
     * @param $idList
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @return int
     */
    public static function getSearchGlobalCount($idList, $seasonId, $sportId, $stateId, $cityId)
    {
        $query = static::whereIn('school.id', $idList);

        if (!empty($seasonId)) {
            $query = $query->where(['school.season_id' => $seasonId]);
        }
        if (!empty($stateId)) {
            $query = $query->where(['school.state_id' => $stateId]);
        }
        if (!empty($cityId)) {
            $query = $query->where(['school.city_id' => $cityId]);
        }
        if (!empty($sportId)) {
            $query = $query->leftJoin('school_sport', 'school.id', '=', 'school_sport.school_id')
                           ->where(['school_sport.sport_id' => $sportId]);
        }

        return $query->count();
    }

    /**
     * @param $request
     * @return array
     */
    public static function getSchoolSports($request)
    {
        $sportsList = [];
        $school = static::active()->where(['id' => $request->get('schoolId')])->first();

        if (!empty($school) && count($school->sports)) {
            foreach ($school->sports as $sport) {
                $teamType = [];
                $teams = Team::where(['school_id' => $school->id, 'sport_id' => $sport->sport_id])->get();

                if (count($teams) > 1) {
                    foreach ($teams as $team) {
                        $teamType[] = $team->getTeamTypeFull();
                    }
                }

                $sportsList[] = [
                    'id' => $sport->sport->id,
                    'title' => $sport->sport->title,
                    'logoUrl' => '/img/sports/' . strtolower($sport->sport->title) . '.svg',
                    'teamType' => $teamType
                ];
            }
        }

        return [
            'poweredBy' => (!empty($school->socials) && !empty($school->socials->kross_show)),
            'sports' => $sportsList
        ];
    }

    /**
     * getAllSchoolList
     *
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function getAllSchoolList($request)
    {
        return static::selectRaw('
        school.id id,
        school.name name,
        school.state_id state_id,
        school.county_id county_id,
        school.is_logo is_logo,
        CONCAT(user.first_name, " ", user.last_name) principal,
        states.name stateName,
        states.abbr stateShortName,
        counties.name countyName
        ')->where([
            'school.active' => 1,
            'school.visible' => 1,
            'school.season_id' => $request->get('seasonId')
        ])->with('teams')->with('socials')->with('sports')->leftJoin('other_person_info', function ($join) {
            $join->on('other_person_info.school_id', '=', 'school.id')
                 ->where('other_person_info.other_type_id', '=', 19);
        })->leftJoin('user', 'user.id', 'other_person_info.user_id')
                     ->leftJoin(env('DB_DATABASE_GLOBAL') . '.states', 'states.id', 'school.state_id')
                     ->leftJoin(env('DB_DATABASE_GLOBAL') . '.counties', 'counties.id', 'school.county_id');
    }

    /**
     * createAbcSchools
     *
     * @param $abcData
     * @return array
     */
    public static function createAbcSchools($abcData)
    {
        $data = [];
        foreach ($abcData as $item) {
            $str = mb_strimwidth($item, 0, 1);
            if (intval($str)) {
                $i = '#';
            } else {
                $i = $str;
            }
            $data[] = $i;
        }

        return $data;
    }

    /**
     * sortAbcSchoolInCounty
     *
     * @param $data
     * @return array
     */
    public static function sortAbcSchoolInCounty($data)
    {
        $arr = array_values(array_unique($data));
        sort($arr);
        reset($arr);
        if ($arr[0] == '#') {
            array_shift($arr);
            array_push($arr, '#');
        }

        return $arr;
    }

    /**
     * sortAbcSchoolAll
     *
     * @param $data
     * @return array
     */
    public static function sortAbcSchoolAll($data)
    {
        $arr = array_count_values($data);
        ksort($arr);
        reset($arr);
        if (isset($arr['#'])) {
            $count = $arr['#'];
            array_shift($arr);
            $arr['#'] = $count;
        }

        return $arr;
    }

    public static function getLocationMostPopular($seasonId, $isDefault = false)
    {
        if ($isDefault) {
            $school = static::where(['season_id' => $seasonId, 'is_default_location' => 1])->first();
        } else {
            $school = static::where(['season_id' => $seasonId])->first();
        }

        return $school;
    }

    public static function getLocationCityList($seasonId)
    {
        return static::where(['season_id' => $seasonId])->whereRaw('city_id IS NOT NULL')->groupBy('city_id')
                     ->pluck('city_id');
    }

    public static function generateAllSchoolSearch($schools, $inGlobal = false)
    {
        $data = [];
        foreach ($schools as $school) {
            $dump = [];
            foreach ($schools as $item) {
                if ($school->county_id == $item->county_id) {
                    $itemTrans = (new Manager)->createData(new Item($item, new SchoolAllTransformer))->toArray();
                    $dump[] = $itemTrans['data'];
                }
            }
            $data[$school->county_id] = [
                'countyId' => $school->county_id,
                'stateId' => $school->state_id,
                'schools' => $dump
            ];
            if ($inGlobal) {
                break;
            }
        }
        $result = array_values($data);

        return $result;
    }

    public static function getAllSchoolSearchList($request)
    {
        $query = static::where(['season_id' => $request->get('seasonId')]);
        if (!empty($request->get('stateId'))) {
            $query = $query->where(['state_id' => $request->get('stateId')]);
        }
        if (!empty($request->get('countyId')) && is_array($request->get('countyId'))) {
            $query = $query->whereIn('county_id', $request->get('countyId'));
        } elseif (!empty($request->get('countyId'))) {
            $query = $query->where('county_id', $request->get('countyId'));
        }

        return $query->pluck('id')->toArray();
    }

    public static function getFullDataAllSchoolSearch($request, $uniqueIds)
    {
        return static::getAllSchoolList($request)->whereIn('school.id', $uniqueIds)
                     ->orderByRaw('FIELD(school.id, ' . implode(',', $uniqueIds) . ')')->get();
    }

    public static function allSchoolsTree($schools)
    {
        $dataAll = $schools->pluck('name')->toArray();

        $countyIds = $schools->unique('county_id')->pluck('county_id');
        $counties = Counties::whereIn('id', $countyIds)->get();

        $abc = [];
        foreach ($countyIds as $val) {
            $abcData = $schools->where('county_id', '=', $val)->pluck('name')->toArray();
            $countyAbc = School::createAbcSchools($abcData);
            $abc[$val] = School::sortAbcSchoolInCounty($countyAbc);
        }

        $allAbc = School::createAbcSchools($dataAll);
        $sortAbc = School::sortAbcSchoolAll($allAbc);
        $schoolsAbc = [];
        foreach ($sortAbc as $key => $value) {
            $schoolsAbc[] = [
                'char' => $key,
                'count' => $value
            ];
        }

        $stateIds = $counties->unique('state_id')->pluck('state_id');
        $states = States::whereIn('id', $stateIds)->get();

        return [
            'states' => $states,
            'counties' => $counties,
            'abc' => $abc,
            'schoolsAbc' => $schoolsAbc
        ];
    }

    public static function getSchoolsFromFavorites($ids, $request)
    {
        $query = static::selectRaw('school.id id, school.name name, school.state_id state_id, school.county_id county_id, school.is_logo is_logo, CONCAT(user.first_name, " ", user.last_name) principal, states.name stateName, states.abbr stateShortName, counties.name countyName')
                       ->where(['school.active' => 1, 'school.visible' => 1])->whereIn('school.id', $ids);

        if (!empty($request->get('sportId'))) {
            $query = $query->where(['school_sport.sport_id' => $request->get('sportId')]);
        }
        $modelData = $query->leftJoin('other_person_info', function ($join) {
            $join->on('other_person_info.school_id', '=', 'school.id')
                 ->where('other_person_info.other_type_id', '=', 19);
        })->leftJoin('user', 'user.id', 'other_person_info.user_id')
                           ->leftJoin('school_sport', 'school_sport.school_id', 'school.id')
                           ->leftJoin(env('DB_DATABASE_GLOBAL') . '.states', 'states.id', 'school.state_id')
                           ->leftJoin(env('DB_DATABASE_GLOBAL') . '.counties', 'counties.id', 'school.county_id')
                           ->with('teams')->with('socials')->with('sports')->get();

        return (new Manager)->createData(new Collection($modelData, new SchoolAllTransformer))->toArray();
    }
}
