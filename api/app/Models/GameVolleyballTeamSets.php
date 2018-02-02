<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $game_id
 * @property integer $team_id
 * @property integer $set
 * @property integer $attack_k
 * @property integer $attack_e
 * @property integer $attack_ta
 * @property float $attack_pct
 * @property integer $side_out1
 * @property integer $side_out2
 * @property string $tmp_data
 * @property Game $game
 * @property Team $team
 */
class GameVolleyballTeamSets extends Base
{
    /**
     * @var array
     */
    protected $fillable = [
        'game_id',
        'team_id',
        'set',
        'attack_k',
        'attack_e',
        'attack_ta',
        'attack_pct',
        'side_out1',
        'side_out2',
        'tmp_data'
    ];

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
    public function team()
    {
        return $this->belongsTo('App\Models\Team');
    }
}
