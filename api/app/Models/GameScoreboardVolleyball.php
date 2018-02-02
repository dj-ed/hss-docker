<?php

namespace App\Models;

/**
 * @property integer $id
 * @property integer $game_id
 * @property integer $team_id
 * @property integer $s1
 * @property integer $s2
 * @property integer $s3
 * @property integer $s4
 * @property integer $s5
 * @property string $tmp_data
 * @property Game $game
 */
class GameScoreboardVolleyball extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_scoreboard_volleyball';

    /**
     * @var array
     */
    protected $fillable = ['game_id', 'team_id', 's1', 's2', 's3', 's4', 's5', 'tmp_data'];

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

    public function summ()
    {
        $win = null;
        $scores = static::find()->where(['game_id' => $this->game_id])->all();

        if (count($scores) == 2) {
            $win = 0;
            $myScore = ($scores[0]['team_id'] == $this->team_id) ? $scores[0] : $scores[1];
            $opponentScore = ($scores[0]['team_id'] == $this->team_id) ? $scores[1] : $scores[0];

            for ($i = 1; $i <= 5; $i++) {
                if ($myScore['s' . $i] > $opponentScore['s' . $i]) {
                    $win++;
                }
            }
        }

        return $win;
    }


    public static function getGameScoreboard($game)
    {
        $rez['columns'] = ['s1', 's2', 's3', 's4', 's5', 'pts'];
        $all_scores = (new static())::where('game_id', $game->id)->get()->toArray();

        if (!empty($all_scores)) {
            foreach ($all_scores as $val) {
                if ($val['team_id'] == $game->team_id) {
                    $val['pts'] = $game->score_team;
                    $rez['main'] = $val;
                } else {
                    $val['pts'] = $game->score_opponent;
                    $rez['opponent'] = $val;
                }
            }
        }
        return $rez;
    }
}
