<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $date
 * @property string $action
 * @property string $description
 * @property integer $school_id
 * @property string $details
 * @property User $user
 */
class Log extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'log';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'date', 'action', 'description', 'school_id', 'details'];

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
}
