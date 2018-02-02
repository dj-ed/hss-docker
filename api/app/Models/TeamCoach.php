<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $team_id
 * @property integer $user_id
 * @property integer $school_id
 * @property string $coach_type
 * @property string $bio
 * @property string $street
 * @property string $apl_suite
 * @property string $address_line2
 * @property string $city
 * @property integer $state_id
 * @property integer $zip
 * @property string $home_phone
 * @property integer $home_ext
 * @property string $mobile
 * @property integer $active
 * @property integer $status
 * @property integer $visible
 * @property string $tmp_data
 * @property Team $team
 * @property User $user
 * @property School $school
 * @property CoachesCorner $coachesCorners
 * @property Favorite $favorites
 */
class TeamCoach extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_coach';

    /**
     * @var array
     */
    protected $fillable = [
        'team_id',
        'user_id',
        'school_id',
        'coach_type',
        'bio',
        'street',
        'apl_suite',
        'address_line2',
        'city',
        'state_id',
        'zip',
        'home_phone',
        'home_ext',
        'mobile',
        'active',
        'status',
        'visible',
        'tmp_data'
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
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function school()
    {
        return $this->belongsTo('App\Models\School');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coachesCorners()
    {
        return $this->hasMany('App\Models\CoachesCorner');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function favorites()
    {
        return $this->morphMany('App\Models\Favorite', 'model');
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeCoachHead($query)
    {
        return $query->where(['coach_type' => 'head']);
    }

    /**
     * @param $chunk
     * @param $seasonId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public static function getSearchResult($chunk, $seasonId)
    {
        return static::with('user')->with('school')->whereIn('team_coach.id', $chunk)
                     ->leftJoin('team', 'team_coach.team_id', 'team.id')->where(['team.season_id' => $seasonId])
                     ->orderByRaw('FIELD(team_coach.id, ' . implode(',', $chunk) . ')')->get();
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
        $query = static::leftJoin('team', 'team_coach.team_id', '=', 'team.id');

        if (!empty($seasonId)) {
            $query = $query->where(['team.season_id' => $seasonId]);
        }
        if (!empty($sportId)) {
            $query = $query->where(['team.sport_id' => $sportId]);
        }

        if (!empty($stateId) || !empty($cityId)) {
            $query = $query->leftJoin('school', 'team_coach.school_id', '=', 'school.id');
        }
        if (!empty($stateId)) {
            $query = $query->where(['school.state_id' => $stateId]);
        }
        if (!empty($cityId)) {
            $query = $query->where(['school.city_id' => $cityId]);
        }

        return $query->pluck('team_coach.id')->toArray();
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
        $query = static::whereIn('team_coach.id', $idList);

        if (!empty($seasonId) || !empty($sportId)) {
            $query = $query->leftJoin('team', 'team_coach.team_id', '=', 'team.id');
        }
        if (!empty($seasonId)) {
            $query = $query->where(['team.season_id' => $seasonId]);
        }
        if (!empty($sportId)) {
            $query = $query->where(['team.sport_id' => $sportId]);
        }

        if (!empty($stateId) || !empty($cityId)) {
            $query = $query->leftJoin('school', 'team_coach.school_id', '=', 'school.id');
        }
        if (!empty($stateId)) {
            $query = $query->where(['school.state_id' => $stateId]);
        }
        if (!empty($cityId)) {
            $query = $query->where(['school.city_id' => $cityId]);
        }

        return $query->count();
    }

    public static function teamCoach($teamId)
    {
        $coach = static::with('user')->where(['team_id' => $teamId])->get();

        $data['coach'] = $coach->where('coach_type', 'head')->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->user->first_name . ' ' . $item->user->last_name,
            ];
        })->values()->first();

        $data['assistant'] = $coach->where('coach_type', 'assistant')->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->user->first_name . ' ' . $item->user->last_name,
            ];
        })->values()->first();

        return $data;
    }
}
