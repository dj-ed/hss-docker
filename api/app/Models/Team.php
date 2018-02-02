<?php

namespace App\Models;

use App\Models\Globals\Counties;
use App\Models\Globals\States;
use App\Transformers\TeamAllTransformer;
use Storage;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

/**
 * @property integer $id
 * @property integer $school_id
 * @property integer $type_id
 * @property integer $season_id
 * @property integer $sport_id
 * @property string $name
 * @property string $main_color
 * @property string $second_color
 * @property integer $status
 * @property integer $visible
 * @property integer $active
 * @property string $tmp_data
 * @property integer $p_added
 * @property integer $c_added
 * @property integer $edit_uid
 * @property integer $use_school_logo
 * @property integer $is_logo
 * @property integer $gender_id
 * @property School $school
 * @property TeamType $teamType
 * @property TeamGender $teamGender
 * @property Season $season
 * @property Game $games
 * @property GameVolleyballTeamSets $gameVolleyballTeamSets
 * @property Player $players
 * @property TeamCoach $teamCoaches
 * @property User $users
 */
class Team extends Base
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team';

    /**
     * @var array
     */
    protected $fillable = [
        'school_id',
        'type_id',
        'season_id',
        'sport_id',
        'name',
        'main_color',
        'second_color',
        'status',
        'visible',
        'active',
        'tmp_data',
        'p_added',
        'c_added',
        'edit_uid',
        'use_school_logo',
        'is_logo',
        'gender_id'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function active()
    {
        return static::where(['active' => 1, 'visible' => 1]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teamType()
    {
        return $this->belongsTo('App\Models\TeamType', 'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teamGender()
    {
        return $this->belongsTo('App\Models\TeamGender', 'gender_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function games()
    {
        return $this->hasMany('App\Models\Game', 'opponent_team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function gameVolleyballTeamSets()
    {
        return $this->hasMany('App\Models\GameVolleyballTeamSets');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function players()
    {
        return $this->hasMany('App\Models\Player');
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
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sport()
    {
        return $this->belongsTo('App\Models\Sports');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany('App\Models\Favorite', 'model');
    }

    /**
     * getLogo
     *
     * @return string
     */
    public function getLogo()
    {
        if ($this->is_logo || $this->use_school_logo) {
            return Storage::disk('s3')->url('uploads/teams/'.$this->id.'/logo.png');
        }
        //TODO: DEFAULT LOGO
        return '/img/default_team.svg';
    }

    /**
     * getLogoById
     *
     * @param $id
     * @param bool $is_logo
     * @param bool $useSchoolLogo
     * @return string
     */
    public static function getLogoById($id, $is_logo = true, $useSchoolLogo = false)
    {
        if ($is_logo || $useSchoolLogo) {
            return Storage::disk('s3')->url('uploads/teams/'.$id.'/logo.png');
        }
        //TODO: DEFAULT LOGO
        return '/img/default_team.svg';
    }

    /**
     * getPhotoById
     *
     * @param $id
     * @return string
     */
    public static function getPhotoById($id)
    {
        $userImgUrl = 'uploads/teams/'.$id.'/photo.jpg';
        if (Storage::disk('s3')->exists($userImgUrl)) {
            return Storage::disk('s3')->url('uploads/teams/'.$id.'/photo.png');
        }
        //TODO: DEFAULT LOGO
        return '';
    }

    /**
     * generateShortName
     *
     * @param $str
     * @return string
     */
    public static function generateShortName($str)
    {
        return strtoupper(substr($str, 0, 3));
    }

    /**
     * getTeamListIds
     *
     * @param $params
     * @return \Illuminate\Support\Collection
     */
    public static function getTeamListIds($params)
    {
        $team_list = Team::where([
            'sport_id' => $params['sportId'],
            'team.season_id' => $params['seasonId'],
        ]);
        if (!empty($params['varsityId']) && !empty($params['genderId'])) {
            $gender = $params['genderId'];
            $team_list->where('type_id', TeamType::getTeamTypeId($params['varsityId']))->where('gender_id', $gender);
        } else if (!empty($params['genderId'])) {
            $gender = $params['genderId'];
            $team_list->where('gender_id', $gender);
        }

        if (!empty($params['stateId']) || !empty($params['cityId'])) {
            $team_list->leftJoin('school', 'school.id', 'team.school_id');
        }
        if (!empty($params['stateId'])) {
            $team_list->where(['school.state_id' => $params['stateId']]);
        }
//        if (!empty($params['cityId'])) {
//            $team_list->where(['school.city_id' => $params['cityId']]);
//        }

        return $team_list->pluck('team.id');
    }

    /**
     * @param $chunk
     * @param $seasonId
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getSearchResult($chunk, $seasonId)
    {
        return static::select([
            'team.id',
            'team.name AS name',
            'school.district',
            'counties.name AS countyName',
            'team.is_logo',
            'team.use_school_logo',
            'team.sport_id'
        ])->whereIn('team.id', $chunk)->where(['team.season_id' => $seasonId])
                     ->join('school', 'school.id', '=', 'team.school_id', 'left')
                     ->join(env('DB_DATABASE_GLOBAL').'.counties', 'counties.id', '=', 'school.county_id', 'left')
                     ->orderByRaw('FIELD(team.id, '.implode(',', $chunk).')')->get();
    }

    /**
     * @param $seasonId
     * @param $sportId
     * @param $stateId
     * @param $cityId
     * @return array
     */
    public static function getSearchSeasonList($seasonId, $sportId, $stateId, $cityId)
    {
        $query = static::where(['team.season_id' => $seasonId]);

        if (!empty($sportId)) {
            $query = $query->where(['team.sport_id' => $sportId]);
        }

        if (!empty($stateId) || !empty($cityId)) {
            $query = $query->leftJoin('school', 'team.school_id', '=', 'school.id');
        }
        if (!empty($stateId)) {
            $query = $query->where(['school.state_id' => $stateId]);
        }
        if (!empty($cityId)) {
            $query = $query->where(['school.city_id' => $cityId]);
        }

        return $query->pluck('team.id')->toArray();
    }

    /**
     * getAllTeamList
     *
     * @param $seasonId
     * @param $sportId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function getAllTeamList($seasonId, $sportId)
    {
        $query = static::selectRaw('states.id AS statesId, states.name AS stateName, states.abbr AS stateShortName, 
                                  counties.id AS countyId, counties.name AS countyName, team.id AS teamId, 
                                  team.sport_id AS sportId, team.school_id AS schoolId, school.name AS schoolName,
                                  team_gender.title AS genderName, team_type.title AS ligaName,CONCAT(user.first_name," ",user.last_name) AS principal')
                       ->whereRaw('team.season_id = '.$seasonId);

        if (!empty($sportId)) {
            $query = $query->whereRaw('team.sport_id = '.$sportId);
        }

        return $query->whereRaw('team.sport_id = school_sport.sport_id')->whereRaw('states.id = counties.state_id')
                     ->whereRaw('school_sport.school_id = school.id')->whereRaw('counties.id = school.county_id')
                     ->leftJoin('school', 'school.id', 'team.school_id')
                     ->leftJoin('other_person_info', function ($join) {
                         $join->on('other_person_info.school_id', '=', 'team.school_id')
                              ->where('other_person_info.other_type_id', '=', 19);
                     })->leftJoin('user', 'user.id', 'other_person_info.user_id')
                     ->leftJoin('team_gender', 'team_gender.id', '=', 'team.gender_id')
                     ->leftJoin('team_type', 'team_type.id', '=', 'team.type_id')
                     ->leftJoin('school_sport', 'school_sport.school_id', 'school.id')
                     ->leftJoin(env('DB_DATABASE_GLOBAL').'.states', 'states.id', 'school.state_id')
                     ->leftJoin(env('DB_DATABASE_GLOBAL').'.counties', 'counties.id', 'school.county_id')->get();
    }

    /**
     * generateResponseData
     *
     * @param $teams
     * @return array
     */
    public static function generateResponseData($teams)
    {
        $states = $genders = $leagues = $counties = $sports = $schools = $result = [];
        foreach ($teams as $team) {
            $states[$team['statesId']] = [
                'statesId' => $team['statesId'],
                'stateName' => $team['stateName'],
                'stateShortName' => $team['stateShortName'],
                'stateLogo' => States::getFlagLogo($team['stateName'])
            ];
            $counties[$team['countyId']] = [
                'countyId' => $team['countyId'],
                'countyName' => $team['countyName'],
                'countyShortName' => Counties::getShortName($team['countyName']),
            ];
            $sports[$team['sportId']] = [
                'sportId' => $team['sportId']
            ];
            $schools[$team['schoolId']] = [
                'schoolId' => $team['schoolId'],
                'schoolName' => $team['schoolName'],
                'principal' => $team['principal']
            ];
            $genders[$team['schoolId']][] = $team['genderName'];
            $leagues[$team['schoolId']][] = $team['genderName'].' '.$team['ligaName'];

            $result[$team['statesId']][$team['countyId']][$team['sportId']][$team['schoolId']][] = $team['teamId'];
        }

        $allTeams = [];
        foreach ($result as $key => $value) {
            $data = $states[$key];
            $countState = 0;
            foreach ($value as $countyKey => $countyValue) {
                $data['county'][$countyKey] = $counties[$countyKey];
                $countCounty = 0;
                foreach ($countyValue as $sportKey => $sportValue) {
                    $data['county'][$countyKey]['sports'][$sportKey] = $sports[$sportKey];
                    $countTeams = 0;
                    foreach ($sportValue as $schoolKey => $schoolValue) {
                        $count = count($schoolValue);
                        $data['county'][$countyKey]['sports'][$sportKey]['schools'][$schoolKey] = $schools[$schoolKey];
                        $data['county'][$countyKey]['sports'][$sportKey]['schools'][$schoolKey]['genders'] = array_unique($genders[$schoolKey]);
                        $data['county'][$countyKey]['sports'][$sportKey]['schools'][$schoolKey]['leagues'] = $leagues[$schoolKey];
                        $data['county'][$countyKey]['sports'][$sportKey]['schools'][$schoolKey]['count'] = $count;
                        $countTeams += $count;
                    }
                    $data['county'][$countyKey]['sports'][$sportKey]['count'] = $countTeams;
                    $data['county'][$countyKey]['sports'][$sportKey]['schools'] = array_values($data['county'][$countyKey]['sports'][$sportKey]['schools']);
                    $data['county'][$countyKey]['sports'] = array_values($data['county'][$countyKey]['sports']);
                    $countCounty += $countTeams;
                }
                $data['county'][$countyKey]['count'] = $countCounty;
                $data['county'] = array_values($data['county']);
                $countState += $countCounty;
            }
            $data['count'] = $countState;
            $allTeams[] = $data;
        }

        return $allTeams;
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
        $query = static::whereIn('team.id', $idList);

        if (!empty($seasonId)) {
            $query = $query->where(['team.season_id' => $seasonId]);
        }
        if (!empty($sportId)) {
            $query = $query->where(['team.sport_id' => $sportId]);
        }

        if (!empty($stateId) || !empty($cityId)) {
            $query = $query->leftJoin('school', 'team.school_id', '=', 'school.id');
        }
        if (!empty($stateId)) {
            $query = $query->where(['school.state_id' => $stateId]);
        }
        if (!empty($cityId)) {
            $query = $query->where(['school.city_id' => $cityId]);
        }

        return $query->count();
    }

    /**
     * getSchoolList
     *
     * @param $request
     * @return array
     */
    public static function getSchoolList($request)
    {
        $query = static::active()->where(['season_id' => $request->get('seasonId')]);
        if (!empty($request->get('sportId'))) {
            $query = $query->where(['sport_id' => $request->get('sportId')]);
        }

        return $query->pluck('school_id')->toArray();
    }

    public function getTeamTypeFull($separator = ' - ', $full = false)
    {
        return $this->teamGender->title.$separator.($full) ? $this->teamType->full_title : $this->teamType->title;
    }

    public static function getLocationTeams($request)
    {
        $query = static::selectRaw('
                    team.id, 
                    team.name, 
                    team.is_logo, 
                    team.use_school_logo, 
                    school.state_id, 
                    school.city_id, 
                    team_gender.title genderName,
                    team_type.title varsityName,
                    team_type.full_title varsityFullName,
                    school.name schoolName,
                    school.is_logo schoolIsLogo,
                    school.id schoolId,
                    team.sport_id
                   ')->where(['team.season_id' => $request->get('seasonId')])
                       ->leftJoin('school', 'school.id', 'team.school_id')
                       ->leftJoin('team_gender', 'team_gender.id', 'team.gender_id')
                       ->leftJoin('team_type', 'team_type.id', 'team.type_id');

        if (!empty($request->get('stateId'))) {
            $query = $query->where(['school.state_id' => $request->get('stateId')]);
        }

        if (!empty($request->get('cityId'))) {
            $query = $query->where(['school.city_id' => $request->get('cityId')]);
        }

        return $query->orderBy('school.id', 'DESC')->get();
    }

    public static function getTeamsFromFavorites($ids)
    {
        $modelData = static::with('teamGender')->with('teamType')->whereIn('id', $ids)->get();

        return (new Manager)->createData(new Collection($modelData, new TeamAllTransformer))->toArray();
    }
}
