<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $content_id
 * @property integer $player_id
 * @property UserContent $userContent
 * @property Player $player
 */
class UserContentPlayers extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['content_id', 'player_id'];

    /**
     * Indicates if the model should be timestamped.
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userContent()
    {
        return $this->belongsTo('App\Models\UserContent', 'content_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }
}
