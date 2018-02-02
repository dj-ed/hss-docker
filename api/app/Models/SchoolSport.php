<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $school_id
 * @property integer $sport_id
 * @property School $school
 * @property Sports $sport
 */
class SchoolSport extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'school_sport';

    /**
     * @var array
     */
    protected $fillable = ['school_id', 'sport_id'];

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

    public function sport()
    {
        return $this->belongsTo('App\Models\Sports');
    }
}
