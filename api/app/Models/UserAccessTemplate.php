<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property integer $sport_id
 * @property string $rules
 * @property User $user
 */
class UserAccessTemplate extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_access_template';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'sport_id', 'rules'];

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
