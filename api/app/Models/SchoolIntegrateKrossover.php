<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $school_id
 * @property string $login
 * @property string $password
 * @property string $token
 * @property string $token_expire
 * @property string $team_list
 * @property School $school
 */
class SchoolIntegrateKrossover extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'school_integrate_krossover';

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'login', 'password', 'token', 'token_expire', 'team_list'];

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
}
