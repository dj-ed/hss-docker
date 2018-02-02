<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $list_id
 * @property integer $user_id
 * @property Lists $list
 * @property User $user
 */
class UserList extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_list';

    /**
     * @var array
     */
    protected $fillable = ['list_id', 'user_id'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function list()
    {
        return $this->belongsTo('App\Models\Lists');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
