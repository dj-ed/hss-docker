<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $player_id
 * @property integer $position_id
 * @property Player $player
 */
class XPlayerPositions extends Base
{
    /**
     * @var array
     */
    protected $fillable = ['player_id', 'position_id'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }
}
