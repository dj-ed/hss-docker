<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $school_id
 * @property integer $season_id
 * @property string $title
 * @property string $heading
 * @property string $first_text
 * @property string $last_text
 * @property string $paragraph
 * @property string $date
 * @property integer $confirmed
 * @property string $initial
 * @property User $user
 * @property School $school
 * @property Season $season
 */
class Obligation extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'obligation';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'school_id',
        'season_id',
        'title',
        'heading',
        'first_text',
        'last_text',
        'paragraph',
        'date',
        'confirmed',
        'initial'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function season()
    {
        return $this->belongsTo('App\Models\Season');
    }
}
