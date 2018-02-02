<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $game_id
 * @property integer $team_id
 * @property integer $q1
 * @property integer $q2
 * @property integer $q3
 * @property integer $q4
 * @property integer $ot
 * @property string $tmp_data
 * @property Game $game
 */
class GameScoreboardBasketball extends Base
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'game_scoreboard_basketball';

    /**
     * @var array
     */
    protected $fillable = ['game_id', 'team_id', 'q1', 'q2', 'q3', 'q4', 'ot', 'tmp_data'];

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

    public static function getGameScoreboard($game)
    {
        $rez['columns'] = ['q1','q2','q3', 'ot','pts'];
        $all_scores = (new static())::where('game_id',$game->id)->get()->toArray();

        if (!empty($all_scores)) {
            foreach ($all_scores as $val) {
                if ($val['team_id'] == $game->team_id) {
                    $val['pts']=$game->score_team;
                    $rez['main'] = $val;
                } else {
                    $val['pts']=$game->score_opponent;
                    $rez['opponent'] = $val;
                }
            }
        }
        return $rez;
    }
}
