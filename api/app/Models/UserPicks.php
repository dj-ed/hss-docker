<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $type_id
 * @property integer $menu_visible
 * @property string $type
 * @property User $user
 */
class UserPicks extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'type_id', 'menu_visible', 'type'];

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
