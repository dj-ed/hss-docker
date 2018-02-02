<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $game_id
 * @property integer $user_id
 * @property string $created_at
 * @property Game $game
 * @property User $user
 */
class ExelUploads extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['game_id', 'user_id', 'created_at'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function game()
    {
        return $this->belongsTo('App\Models\Game');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
