<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $player_id
 * @property string $last_update
 * @property string $type
 * @property string $type_id_list
 * @property Player $player
 */
class PlayerWall extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'player_wall';

    /**
     * @var array
     */
    protected $fillable = ['player_id', 'last_update', 'type', 'type_id_list'];

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
