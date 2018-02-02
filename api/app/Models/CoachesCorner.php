<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $school_id
 * @property integer $team_coach_id
 * @property string $title
 * @property string $description
 * @property string $date
 * @property string $status
 * @property string $tmp_data
 * @property integer $edit_uid
 * @property School $school
 * @property TeamCoach $teamCoach
 */
class CoachesCorner extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'coaches_corner';

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'team_coach_id', 'title', 'description', 'date', 'status', 'tmp_data', 'edit_uid'];

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
    public function teamCoach()
    {
        return $this->belongsTo('App\Models\TeamCoach');
    }
}
