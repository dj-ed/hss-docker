<?php

namespace App\Models;

use DB;
use Storage;

/**
 * @property integer $id
 * @property integer $game_id
 * @property integer $player_id
 * @property integer $sets
 * @property integer $attack_k
 * @property integer $attack_e
 * @property integer $attack_ta
 * @property float $attack_pct
 * @property integer $assists
 * @property integer $serve_a
 * @property integer $serve_e
 * @property integer $re
 * @property integer $dig
 * @property integer $block_bs
 * @property integer $block_ba
 * @property integer $block_be
 * @property integer $bhe
 * @property float $pts
 * @property string $tmp_data
 * @property Game $game
 * @property Player $player
 */
class GamePlayerStatVolleyball extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_player_stat_volleyball';

    /**
     * @var array
     */
    protected $fillable = [
        'game_id',
        'player_id',
        'sets',
        'attack_k',
        'attack_e',
        'attack_ta',
        'attack_pct',
        'assists',
        'serve_a',
        'serve_e',
        're',
        'dig',
        'block_bs',
        'block_ba',
        'block_be',
        'bhe',
        'pts',
        'tmp_data'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    static $additionalAttr = [
        'attack_pct' => 'ATTACK PCT',
        'total_blk' => 'TOTAL BLOCKS',
        'pts' => 'PTS'
    ];

    /**
     * @var array
     */
    static $rosterAttr = [
        'sets' => 'SP',
        'attack_k' => 'K',
        'attack_e' => 'E',
        'attack_ta' => 'TA',
        'attack_pct' => '.PCT',
    ];

    /**
     * @var array
     */
    static $seasonAttr = [
        'sp' => 'SP',
        'k' => 'K',
        'e' => 'E',
        'ta' => 'TA',
        'pct' => 'PCT',
        'a' => 'A',
        'sa' => 'SA',
        'se' => 'SE',
        're' => 'RE',
        'dig' => 'DIG',
        'bs' => 'BS',
        'ba' => 'BA',
        'be' => 'BE',
        'bhe' => 'BHE',
        'pts' => 'PTS',
    ];

    /**
     * @var array
     */
    static $personalAttr = [
        'sets' => 'SP',
        'attack_k' => 'K',
        'attack_e' => 'E',
        'attack_ta' => 'TA',
        'attack_pct' => 'PCT',
        'assists' => 'A',
        'serve_a' => 'SA',
        'serve_e' => 'SE',
        're' => 'RE',
        'dig' => 'DIG',
        'block_bs' => 'BS',
        'block_ba' => 'BA',
        'block_be' => 'BE',
        'bhe' => 'BHE',
        'pts' => 'PTS',
    ];

    /**
     * @var array
     */
    static $searchTeamAttr = [
        'sets' => 'SP',
        'attack_pct' => 'AT (%)',
        'total_blk' => 'BLK',
        'pts' => 'PTS'
    ];

    static $searchPlayerAttr = [
        'attack_pct' => 'ATTACK PCT',
        'total_blk' => 'TOTAL BLOCKS',
        'pts' => 'PTS'
    ];

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
    public function player()
    {
        return $this->belongsTo('App\Models\Player');
    }

    public function getPlayersLeaderBoard($ids, $fields = [], $playerLimit = 10)
    {
        $cols_data = [];
        $columns = [
            'attack_pct' => 'ATTACKS, %',
            'attack_k' => 'Kills',
            'assists' => 'Assists',
            'serve_a' => 'SERVICES ACES',
            'dig' => 'Digs',
            'block_bs' => 'BLOCKED SHOTS',
            'block_ba' => 'BLOCK ASSISTS'
        ];
        $ids = $ids->implode(',');
        foreach ($columns as $key => $val) {
            if (empty($fields) || in_array($key, $fields)) {
                $rez = [];
                $rez['stats'] = (!empty($ids)) ? \DB::select('
                SELECT SUM(' . $key . ') points,SUM(attack_pct) attack_pct, SUM(pts) pts, SUM(block_bs)+SUM(block_ba) total_blk,
                user.first_name, user.last_name, player.guard,player.number,
                stat_table.player_id, team.id team_id,team.is_logo is_logo, user.id user_id
                FROM ' . $this->getTable() . ' stat_table
                LEFT JOIN player ON player.id=stat_table.player_id
                LEFT JOIN user ON user.id=player.user_id
                LEFT JOIN team ON team.id=player.team_id
                WHERE player_id IN(' . $ids . ')
                GROUP BY player_id
                ORDER BY points DESC, user_id
                LIMIT ' . $playerLimit) : [];
                $rez['statName'] = $val;
                if (!empty($rez['stats'])) {
                    foreach ($rez['stats'] as $player) {

                        $player->team_logo = Team::getLogoById($player->team_id, $player->is_logo);
                    }
                }

                if (!empty($rez['stats'][0])) {
                    $rez['stats'][0]->user_photo = User::getPhotoById($rez['stats'][0]->user_id);
                }
                $cols_data[] = $rez;
            }
        }

        return $cols_data;
    }

    /**
     * @param $id
     * @return array|\Illuminate\Database\Eloquent\Model|null|static
     */
    public function getSearchPlayerStats($id)
    {
        return (!empty($id)) ? static::selectRaw('SUM(attack_pct) attack_pct, SUM(pts) pts, SUM(block_bs)+SUM(block_ba) total_blk')
                                     ->leftJoin('player', 'player_id', '=', 'player.id')
                                     ->leftJoin('user', 'player.user_id', '=', 'user.id')->where(['player_id' => $id])
                                     ->groupBy('player_id')->orderByDesc('pts')->first() : [];
    }

    public static function getPlayersStats($game)
    {
        $rez = [];
        $opponentGame = $game->getOpponentGame();
        $rez['stats'] = DB::table((new static)->getTable())->select([
            'game_id',
            'pts',
            'sets',
            'attack_k',
            'attack_e',
            'attack_ta',
            'block_bs',
            'block_ba',
            'block_be',
            'user.first_name',
            'user.last_name',
            'player.number',
            'player.id'
        ])->where('game_id', $game->id)->join('player', 'game_player_stat_volleyball.player_id', 'player.id')
                          ->join('user', 'player.user_id', 'user.id')->get();

        $rez['total'] = static::getTotalPStats($rez['stats']);

        if ($opponentGame) {
            $rez['opponentStats'] = DB::table((new static)->getTable())->select([
                'game_id',
                'pts',
                'sets',
                'attack_k',
                'attack_e',
                'attack_ta',
                'block_bs',
                'block_ba',
                'block_be',
                'user.first_name',
                'user.last_name',
                'player.number',
                'player.id'

            ])->where('game_id', $opponentGame->id)
                                      ->join('player', 'game_player_stat_volleyball.player_id', 'player.id')
                                      ->join('user', 'player.user_id', 'user.id')->get();

            $rez['opponentTotal'] = static::getTotalPStats($rez['opponentStats']);
        }

        return $rez;
    }

    public static function getTotalPStats($stats)
    {
        if ($stats->count()) {
            $rez = [
                'pts' => 0,
                'sets' => 0,
                'attack_k' => 0,
                'attack_e' => 0,
                'attack_ta' => 0,
                'block_bs' => 0,
                'block_ba' => 0,
                'block_be' => 0
            ];
            foreach ($stats as $stat) {
                foreach ($rez as $key => $field) {
                    $rez[$key] += $stat->{$key};


                }
            }
            return $rez;
        }
    }

    public static function getTopGamePlayers($game, $limit)
    {
        $rez['columns'] = ['attack_k', 'serve_a', 'dig', 'assists', 'pts'];
        $rez['columnsName'] = ['K', 'Aces', 'Dig', 'AST', 'PTS'];
        $opponentGame = $game->getOpponentGame();
        $rez['main'] = DB::table((new static)->getTable())->select([
            'game_id',
            'attack_k',
            'serve_a',
            'dig',
            'pts',
            'assists',
            'user.first_name',
            'user.last_name',
            'player.number',
            'player.id'
        ])->where('game_id', $game->id)->join('player', 'game_player_stat_volleyball.player_id', 'player.id')
                         ->join('user', 'player.user_id', 'user.id')->orderBy('pts')->limit($limit)->get();
        if ($opponentGame) {
            $rez['opponent'] = DB::table((new static)->getTable())->select([
                'game_id',
                'attack_k',
                'serve_a',
                'dig',
                'pts',
                'assists',
                'user.first_name',
                'user.last_name',
                'player.number',
                'player.id'

            ])->where('game_id', $opponentGame->id)
                                 ->join('player', 'game_player_stat_volleyball.player_id', 'player.id')
                                 ->join('user', 'player.user_id', 'user.id')->orderBy('pts')->limit($limit)->get();
        }

        return $rez;
    }

    public function getTeamRosterStatistics($player_id)
    {
        $data = self::selectRaw('SUM(sets) sets, 
                                ROUND(SUM(attack_k)/SUM(sets),1) attack_k, 
                                ROUND(SUM(attack_e)/SUM(sets),1) attack_e, 
                                ROUND(SUM(attack_ta)/SUM(sets),1) attack_ta, 
                                ROUND(SUM(attack_pct)/SUM(sets),1) attack_pct')->where(['player_id' => $player_id])
                    ->groupBY('player_id')->get()->toArray();

        if (empty($data)) {
            foreach (static::$rosterAttr as $key => $value) {
                $data += [$key => 0];
            }
            $data = [$data];
        }

        return $data;
    }

    public function getTotalStandings($pIds)
    {

        $rez = \DB::select('SELECT SUM(t1.pts) pts, SUM(t1.total_blk) total_blk,SUM(t1.sets) sets,SUM(t1.attack_pct) attack_pct,SUM(t1.assists) assists from
                (SELECT 
                SUM(pts) pts,
                SUM(block_bs)+SUM(block_ba) total_blk,
                SUM(sets) sets,
                SUM(attack_pct) attack_pct,
                SUM(assists) assists,
                ANY_VALUE(player_id) player_id,
                player.team_id team_id
                FROM ' . $this->getTable() . '
                LEFT JOIN player ON player.id=player_id
                WHERE player_id IN(' . $pIds . ')
                GROUP BY player_id) 
                as t1 
                GROUP BY t1.team_id');
        if (!empty($rez[0])) {
            return $rez[0];
        }
    }

    public function getPlayerStandings($pIds, $limit)
    {
        $query = ($limit != null) ? ' LIMIT ' . $limit : '';

        return \DB::select('SELECT 
                SUM(pts) pts,
                SUM(block_bs)+SUM(block_ba) total_blk,
                SUM(sets) sets,
                SUM(attack_k) attack_k,
                SUM(attack_e) attack_e,
                SUM(attack_ta) attack_ta,
                SUM(attack_pct) attack_pct,
                SUM(assists) assists,
                SUM(serve_a) serve_a,
                SUM(serve_e) serve_e,
                SUM(re) re,
                SUM(dig) dig,
                SUM(block_bs) block_bs,
                SUM(block_ba) block_ba,
                SUM(block_be) block_be,
                SUM(bhe) bhe,
                ANY_VALUE(player_id) player_id,
                player.user_id  user_id,
                player.team_id team_id,
                player.number number,
                user.first_name first_name, 
                user.last_name last_name,
                season.title_short season,
                team.name teamName,
                school.id school_id 
                FROM ' . $this->getTable() . ' 
                LEFT JOIN player ON player.id=player_id 
                LEFT JOIN team ON team.id=player.team_id 
                LEFT JOIN season ON season.id=team.season_id 
                LEFT JOIN school ON school.id=team.school_id 
                LEFT JOIN user ON user.id=user_id
                WHERE player_id IN( ' . $pIds . ' )
                GROUP BY player_id ORDER BY pts DESC' . $query);
    }

    /**
     * @param $stats
     * @return mixed
     */
    public static function playerSeasonStats($stats)
    {
        $sets = $attack_k_total = $attack_e_total = $attack_ta_total = $attack_pct_total = $assists_total = $serve_a_total = 0;
        $serve_e_total = $re_total = $dig_total = $block_bs_total = $block_ba_total = $block_be_total = $bhe_total = $pts_total = 0;

        $totals = count($stats);
        foreach ($stats as $num => $game) {
            $sets += $game->sets;

            $attack_k_total += $game->attack_k;
            $attack_e_total += $game->attack_e;
            $attack_ta_total += $game->attack_ta;
            $attack_pct_total += $game->attack_pct;
            $assists_total += $game->assists;
            $serve_a_total += $game->serve_a;
            $serve_e_total += $game->serve_e;
            $re_total += $game->re;
            $dig_total += $game->dig;
            $block_bs_total += $game->block_bs;
            $block_ba_total += $game->block_ba;
            $block_be_total += $game->block_be;
            $bhe_total += $game->bhe;
            $pts_total += $game->pts;

            $dataStat[] = [
                'game' => [
                    'gameNumber' => $totals--,
                    'date' => date('M d', $game->times),
                    'opponent' => Player::getOpponentName($game),
                    'opponentShort' => Team::generateShortName(Player::getOpponentName($game)),
                    'opponentTeamId' => (!empty($game->game->opponent_team_id)) ? $game->game->opponent_team_id : null,
                    'where' => $game->game->where,
                    'score' => $game->game->score_team . ' - ' . $game->game->score_opponent,
                    'result' => (empty($game->game->win)) ? 'L' : 'W',
                ],
                'data' => [
                    'sp' => $game->sets,
                    'k' => $game->attack_k,
                    'e' => $game->attack_e,
                    'ta' => $game->attack_ta,
                    'pct' => str_replace('0.', '.', $game->attack_pct),
                    'a' => $game->assists,
                    'sa' => $game->serve_a,
                    'se' => $game->serve_e,
                    're' => $game->re,
                    'dig' => $game->dig,
                    'bs' => $game->block_bs,
                    'ba' => $game->block_ba,
                    'be' => $game->block_be,
                    'bhe' => $game->bhe,
                    'pts' => $game->pts,
                ]
            ];
        }
        if (!empty($sets)) {
            $dataApg = [
                'sp' => $sets,
                'k' => (!empty($attack_k_total)) ? number_format($attack_k_total / $sets, 1) : 0,
                'e' => (!empty($attack_e_total)) ? number_format($attack_e_total / $sets, 1) : 0,
                'ta' => (!empty($attack_ta_total)) ? number_format($attack_ta_total / $sets, 1) : 0,
                'pct' => str_replace('0.', '.', (!empty($attack_pct_total)) ? number_format($attack_pct_total / $sets, 1) : 0),
                'a' => (!empty($assists_total)) ? number_format($assists_total / $sets, 1) : 0,
                'sa' => (!empty($serve_a_total)) ? number_format($serve_a_total / $sets, 1) : 0,
                'se' => (!empty($serve_e_total)) ? number_format($serve_e_total / $sets, 1) : 0,
                're' => (!empty($re_total)) ? number_format($re_total / $sets, 1) : 0,
                'dig' => (!empty($dig_total)) ? number_format($dig_total / $sets, 1) : 0,
                'bs' => (!empty($block_bs_total)) ? number_format($block_bs_total / $sets, 1) : 0,
                'ba' => (!empty($block_ba_total)) ? number_format($block_ba_total / $sets, 1) : 0,
                'be' => (!empty($block_be_total)) ? number_format($block_be_total / $sets, 1) : 0,
                'bhe' => (!empty($bhe_total)) ? number_format($bhe_total / $sets, 1) : 0,
                'pts' => (!empty($pts_total)) ? number_format($pts_total / $sets, 1) : 0,
            ];
            $dataTotal = [
                'sp' => $sets,
                'k' => $attack_k_total,
                'e' => $attack_e_total,
                'ta' => $attack_ta_total,
                'pct' => str_replace('0.', '.', $attack_pct_total),
                'a' => $assists_total,
                'sa' => $serve_a_total,
                'se' => $serve_e_total,
                're' => $re_total,
                'dig' => $dig_total,
                'bs' => $block_bs_total,
                'ba' => $block_ba_total,
                'be' => $block_be_total,
                'bhe' => $bhe_total,
                'pts' => $pts_total,
            ];
        }

        $data['stats'] = (isset($dataStat)) ? $dataStat : ['game' => [], 'data' => []];
        $data['apg'] = (isset($dataApg)) ? $dataApg : [];
        $data['total'] = (isset($dataTotal)) ? $dataTotal : [];

        return $data;
    }

    public static function personalResultStats($statData)
    {
        $data = $dataStat = [];
        $total_sets = $total_attack_k = $total_attack_e = $total_attack_ta = $total_attack_pct = $total_assists = $total_serve_a = 0;
        $total_serve_e = $total_re = $total_dig = $total_block_bs = $total_block_ba = $total_block_be = $total_bhe = $total_pts = 0;

        foreach ($statData as $stat) {
            $total_sets += $stat->sets;
            $total_attack_k += $stat->attack_k;
            $total_attack_e += $stat->attack_e;
            $total_attack_ta += $stat->attack_ta;
            $total_attack_pct += $stat->attack_pct;
            $total_assists += $stat->assists;
            $total_serve_a += $stat->serve_a;
            $total_serve_e += $stat->serve_e;
            $total_re += $stat->re;
            $total_dig += $stat->dig;
            $total_block_bs += $stat->block_bs;
            $total_block_ba += $stat->block_ba;
            $total_block_be += $stat->block_be;
            $total_bhe += $stat->bhe;
            $total_pts += $stat->pts;

            $dataStat[] = [
                'season' => $stat->season,
                'sets' => $stat->sets,
                'attack_k' => $stat->attack_k,
                'attack_e' => $stat->attack_e,
                'attack_ta' => $stat->attack_ta,
                'attack_pct' => number_format($stat->attack_pct / $stat->sets, 3),
                'assists' => $stat->assists,
                'serve_a' => $stat->serve_a,
                'serve_e' => $stat->serve_e,
                're' => $stat->re,
                'dig' => $stat->dig,
                'block_bs' => $stat->block_bs,
                'block_ba' => $stat->block_ba,
                'block_be' => $stat->block_be,
                'bhe' => $stat->bhe,
                'pts' => $stat->pts,
            ];

        }

        if (count($dataStat) > 1) {
            $dataTotal = [
                'sets' => $total_sets,
                'attack_k' => $total_attack_k,
                'attack_e' => $total_attack_e,
                'attack_ta' => $total_attack_ta,
                'attack_pct' => number_format($total_attack_pct / count($dataStat), 3),
                'assists' => $total_assists,
                'serve_a' => $total_serve_a,
                'serve_e' => $total_serve_e,
                're' => $total_re,
                'dig' => $total_dig,
                'block_bs' => $total_block_bs,
                'block_ba' => $total_block_ba,
                'block_be' => $total_block_be,
                'bhe' => $total_bhe,
                'pts' => $total_pts,
            ];
        }

        $data['stats'] = (isset($dataStat)) ? $dataStat : [];
        $data['total'] = (isset($dataTotal)) ? $dataTotal : [];

        return $data;
    }

    /**
     * @param $statData
     * @return array
     */
    public static function playerStandingStats($statData)
    {
        $dataStat = [];
        foreach ($statData as $stat) {
            $dataStat[] = [
                'player' => [
                    'teamLogo' => Team::getLogoById($stat->team_id, $stat->is_logo),
                    'playerId' => $stat->player_id,
                    'teamId' => $stat->team_id,
                    'number' => $stat->number,
                    'name' => $stat->first_name . ' ' . $stat->last_name,
                    'teamName' => $stat->teamName
                ],
                'data' => [
                    'sets' => $stat->sets,
                    'attack_k' => $stat->attack_k,
                    'attack_e' => $stat->attack_e,
                    'attack_ta' => $stat->attack_ta,
                    'attack_pct' => number_format($stat->attack_pct / $stat->sets, 3),
                    'assists' => $stat->assists,
                    'serve_a' => $stat->serve_a,
                    'serve_e' => $stat->serve_e,
                    're' => $stat->re,
                    'dig' => $stat->dig,
                    'block_bs' => $stat->block_bs,
                    'block_ba' => $stat->block_ba,
                    'block_be' => $stat->block_be,
                    'bhe' => $stat->bhe,
                    'pts' => $stat->pts,
                ]
            ];
        }

        return $dataStat;
    }
}
