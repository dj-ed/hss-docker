<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $school_id
 * @property integer $user_id
 * @property integer $other_type_id
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
 * @property integer $status
 * @property string $tmp_data
 * @property integer $visible
 * @property School $school
 * @property User $user
 * @property UserType $type
 */
class OtherPersonInfo extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'other_person_info';

    /**
     * @var array
     */
    protected $fillable = [
        'school_id',
        'user_id',
        'other_type_id',
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
        'status',
        'tmp_data',
        'visible'
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
    public function school()
    {
        return $this->belongsTo('App\Models\School');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne('App\Models\UserType', 'id', 'other_type_id');
    }
}
