<?php

namespace App\Models;

use App\Models\Globals\City;
use App\Models\Globals\States;
use Cache;
use Carbon\Carbon;
use \Storage;

/**
 * @property integer $id
 * @property integer $school_id
 * @property integer $school2_id
 * @property integer $team_id
 * @property integer $team2_id
 * @property string $first_name
 * @property string $last_name
 * @property string $middle_init
 * @property integer $type_id
 * @property integer $sport_id
 * @property integer $sport2_id
 * @property string $city_id
 * @property integer $state_id
 * @property integer $hometown_team
 * @property integer $zip_code
 * @property string $email
 * @property string $status
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $auth_key
 * @property string $created_at
 * @property string $updated_at
 * @property string $start_page_url
 * @property string $start_page_title
 * @property string $phone
 * @property integer $fav_school_id
 * @property integer $fav_sport_id
 * @property integer $fav_team_id
 * @property integer $admin_level
 * @property string $tmp_data
 * @property integer $edit_uid
 * @property boolean $is_photo
 * @property string $photo_coord
 * @property string $units
 * @property SchoolGeneral $schoolGeneral
 * @property Team $team
 * @property ExelUploads $exelUploads
 * @property Lists $lists
 * @property Log $logs
 * @property MessageCenter $messageCenters
 * @property MessageCenterResponse $messageCenterResponses
 * @property Obligation $obligations
 * @property OtherPersonInfo $otherPersonInfos
 * @property Player $players
 * @property SchoolPerson $schoolPeople
 * @property TeamCoach $teamCoaches
 * @property UserAccess $userAccesses
 * @property UserAccessTemplate $userAccessTemplates
 * @property UserContent $userContents
 * @property UserList $userLists
 * @property UserPicks $userPicks
 * @property States $state
 * @property City $city
 * @property Team $hometownTeam
 * @property UserType $type
 */
class User extends Base
{
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_INVITED = 'invited';
    const STATUS_CANCELED = 'canceled';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * @var array
     */
    protected $fillable = [
        'school_id',
        'school2_id',
        'team_id',
        'team2_id',
        'first_name',
        'last_name',
        'middle_init',
        'type_id',
        'sport_id',
        'sport2_id',
        'state_id',
        'city_id',
        'hometown_team',
        'zip_code',
        'email',
        'status',
        'auth_key',
        'created_at',
        'updated_at',
        'start_page_url',
        'start_page_title',
        'phone',
        'fav_school_id',
        'fav_sport_id',
        'fav_team_id',
        'admin_level',
        'tmp_data',
        'edit_uid',
        'is_photo',
        'photo_coord',
        'units'
    ];

    protected $hidden = ['password_hash', 'password_reset_token'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schoolGeneral()
    {
        return $this->belongsTo('App\Models\SchoolGeneral', 'school_id');
    }

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
    public function type()
    {
        return $this->belongsTo('App\Models\UserType');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exelUploads()
    {
        return $this->hasMany('App\Models\ExelUploads');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lists()
    {
        return $this->hasMany('App\Models\Lists');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function logs()
    {
        return $this->hasMany('App\Models\Log');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messageCenters()
    {
        return $this->hasMany('App\Models\MessageCenter', 'from_uid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messageCenterResponses()
    {
        return $this->hasMany('App\Models\MessageCenterResponse', 'from_uid');
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
    public function players()
    {
        return $this->hasMany('App\Models\Player');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schoolPeople()
    {
        return $this->hasMany('App\Models\SchoolPerson');
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
    public function userAccesses()
    {
        return $this->hasMany('App\Models\UserAccess');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userAccessTemplates()
    {
        return $this->hasMany('App\Models\UserAccessTemplate');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userContents()
    {
        return $this->hasMany('App\Models\UserContent', 'author_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLists()
    {
        return $this->hasMany('App\Models\UserList');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userPicks()
    {
        return $this->hasMany('App\Models\UserPicks');
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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function hometownTeam()
    {
        return $this->hasOne('App\Models\Team', 'id', 'hometown_team');
    }

    public static function getPhotoById($id)
    {
        $user = static::find($id);
        if (!empty($user)) {
            $cacheKey = 'user-photo-' . $user->id;
            $cacheTime = Carbon::now()->addHour(3);
            $currentSeason = Season::getLastActive();

            if (!Cache::has($cacheKey)) {
                Cache::remember($cacheKey, $cacheTime, function () use ($user, $currentSeason) {
                    $userImgUrl = 'uploads/users/' . $user->id . '/thumb.jpg';
                    $userImgUrl2 = 'uploads/users/' . $user->id . '/' . $currentSeason->id . '_thumb.jpg';
                    // TODO rewrite
//                    if (Storage::disk('s3')->exists($userImgUrl2)) {
//                        return Storage::disk('s3')->url($userImgUrl2);
//                    }
                    if ($user->is_photo) {
                        return Storage::disk('s3')->url($userImgUrl);
                    }
                    //default img
                    return '/img/user.svg';
                });
            }
            return Cache::get($cacheKey);
        }
    }

    public function getPhoto()
    {
        $cacheKey = 'user-photo-' . $this->id;
        $cacheTime = Carbon::now()->addHour(3);
        $currentSeason = Season::getLastActive();

        if (!Cache::has($cacheKey)) {
            Cache::remember($cacheKey, $cacheTime, function () use ($currentSeason) {
                $userImgUrl = 'uploads/users/' . $this->id . '/thumb.jpg';
                $userImgUrl2 = 'uploads/users/' . $this->id . '/' . $currentSeason->id . '_thumb.jpg';
                // TODO rewrite
//                if (Storage::disk('s3')->exists($userImgUrl2)) {
//                    return Storage::disk('s3')->url($userImgUrl2);
//                }
                if ($this->is_photo) {
                    return Storage::disk('s3')->url($userImgUrl);
                }
                //default img
                return '/img/user.svg';
            });
        }

        return Cache::get($cacheKey);
    }
}
