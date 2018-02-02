<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $school_id
 * @property integer $user_id
 * @property string $type
 * @property string $school_phone
 * @property string $school_phone_ext
 * @property string $cell_phone
 * @property string $cell_phone_ext
 * @property integer $obligation_confirmed
 * @property integer $type_id
 * @property School $school
 * @property User $user
 */
class SchoolPerson extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_person';

    /**
     * @var array
     */
    protected $fillable = [
        'school_id',
        'user_id',
        'type',
        'school_phone',
        'school_phone_ext',
        'cell_phone',
        'cell_phone_ext',
        'obligation_confirmed',
        'type_id'
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
}
