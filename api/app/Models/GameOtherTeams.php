<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $school_id
 * @property integer $type_id
 * @property string $name
 * @property GameOtherSchools $gameOtherSchool
 * @property TeamType $teamType
 */
class GameOtherTeams extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['school_id', 'type_id', 'name'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function gameOtherSchool()
    {
        return $this->belongsTo('App\Models\GameOtherSchools', 'school_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function teamType()
    {
        return $this->belongsTo('App\Models\TeamType', 'type_id');
    }
}
