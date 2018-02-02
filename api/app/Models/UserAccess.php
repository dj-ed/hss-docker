<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $sport_id
 * @property string $type
 * @property integer $type_id
 * @property string $rules
 * @property integer $obligation_confirmed
 * @property boolean $ws_complete
 * @property User $user
 * @property Sports $sport
 */
class UserAccess extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_access';

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'sport_id', 'type', 'type_id', 'rules', 'obligation_confirmed', 'ws_complete'];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function sport()
    {
        return $this->hasOne('App\Models\Sports', 'id', 'sport_id');
    }
}
