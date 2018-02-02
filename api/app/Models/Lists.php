<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property User $user
 * @property UserList $userLists
 */
class Lists extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'title', 'description'];

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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userLists()
    {
        return $this->hasMany('App\Models\UserList');
    }
}
