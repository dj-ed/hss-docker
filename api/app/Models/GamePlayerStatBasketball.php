<?php

namespace App\Models;

use DB;
use Storage;

/**
 * @property integer $id
 * @property integer $game_id
 * @property integer $player_id
 * @property integer $fgma_1
 * @property integer $fgma_2
 * @property integer $pma_1
 * @property integer $pma_2
 * @property integer $ftma_1
 * @property integer $ftma_2
 * @property integer $reb
 * @property integer $ast
 * @property integer $pf
 * @property integer $stl
 * @property integer $to
 * @property integer $bs
 * @property integer $pts
 * @property string $tmp_data
 * @property Game $game
 * @property Player $player
 */
class GamePlayerStatBasketball extends Base
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'game_player_stat_basketball';

    /**
     * @var array
     */
    protected $fillable = [
        'game_id',
        'player_id',
        'fgma_1',
        'fgma_2',
        'pma_1',
        'pma_2',
        'ftma_1',
        'ftma_2',
        'reb',
        'ast',
        'pf',
        'stl',
        'to',
        'bs',
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
        'eff' => 'EFF',
        'ppg' => 'PPG',
        'fg' => 'FG',
    ];

    /**
     * @var array
     */
    static $rosterAttr = [
        'gp' => 'GP',
        'pts' => 'Pts',
        'reb' => 'Reb',
        'ast' => 'Ast',
        'stl' => 'Stl'
    ];

    /**
     * @var array
     */
    static $seasonAttr = [
        'pts' => 'PTS',
        'fgm' => 'FGM/A',
        'fg' => 'FG (%)',
        'ptm' => '3PTM/A',
        'pt' => '3PT (%)',
        'ftm' => 'FTM/A',
        'ft' => 'FT (%)',
        'ast' => 'AST',
        'to' => 'TO',
        'reb' => 'REB',
        'blk' => 'BLK',
        'stl' => 'STL',
    ];

    /**
     * @var array
     */
    static $personalAttr = [
        'gp' => 'GP',
        'fgma' => 'FGM-A',
        'fg' => 'FG (%)',
        'pma' => '3PM-A',
        'pm' => '3P (%)',
        'ftma' => 'FTM-A',
        'ft' => 'FT (%)',
        'reb' => 'REB',
        'ast' => 'AST',
        'pf' => 'PF',
        'st' => 'ST',
        'to' => 'TO',
        'bs' => 'BLKS',
        'pts' => 'PTS',
    ];

    /**
     * @var array
     */
    static $searchTeamAttr = [
        'fg' => 'FG',
        'fgm_pct' => 'FGM-A',
        '3pt_pct' => '3PTM-A',
        'pts' => 'PTS',
    ];

    /**
     * @var array
     */
    static $searchPlayerAttr = [
        'eff' => 'EFF',
        'ppg' => 'PPG',
        'pts' => 'PTS',
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
            'pts' => 'Points',
            'reb' => 'Rebound',
            'fgma_1%fgma_2' => 'Field Goal PCT',
            'ftma_1' => 'Free Throw',
            'ftma_1%ftma_2' => 'Free Throw PCT',
            'pma_1' => '3 Pointers',
            'pma_1%pma_2' => '3 Pointers PCT',
            'stl' => 'Steals',
            'bs' => 'Blocks',
            'ast' => 'Assists'
        ];

        if (!is_int($ids)) {
            $ids = $ids->implode(',');
        }
        foreach ($columns as $key => $val) {
            if (empty($fields) || in_array($key, $fields)) {
                $rez = [];
                if (strpos($key, '%') != false) {
                    $dbField = explode('%', $key);
                    $sqlStr = 'CONCAT(ROUND(SUM(' . $dbField[0] . ')/SUM(' . $dbField[1] . ')*100,0),"%") points,SUM(' . $dbField[0] . ') ' . $dbField[0];
                    $sqlOrder = 'ORDER BY ' . $dbField[0] . ' DESC, user_id';
                } else {
                    $sqlStr = 'SUM(' . $key . ') points';
                    $sqlOrder = 'ORDER BY points DESC, user_id ';

                }
                $rez['stats'] = (!empty($ids)) ? \DB::select('
                SELECT  ' . $sqlStr . ',
                ROUND((SUM(pts)+SUM(reb)+SUM(ast)+SUM(stl)+SUM(bs)-(SUM(fgma_2)-SUM(fgma_1))-(SUM(ftma_2)-SUM(ftma_1))-SUM(`to`))/COUNT(user.id),0) eff,
                ROUND(SUM(pts)/COUNT(user.id),1) ppg,
                SUM(fgma_1) fg,
                player.guard, user.first_name, user.last_name,player.number,
                stat_table.player_id, team.id team_id, team.is_logo is_logo, user.id user_id
                FROM ' . $this->getTable() . ' stat_table
                LEFT JOIN player ON player.id=stat_table.player_id
                LEFT JOIN user ON user.id=player.user_id
                LEFT JOIN team ON team.id=player.team_id
                WHERE player_id IN(' . $ids . ')
                GROUP BY player_id
                ' . $sqlOrder . ' 
                LIMIT ' . $playerLimit) : [];

                $rez['statName'] = $val;
                if (!empty($rez['stats'])) {
                    foreach ($rez['stats'] as $player) {
                        $player->team_logo = Team::getLogoById($player->team_id, $player->is_logo);
                    }
                }
                if (!empty($rez['stats'])) {
                    foreach ($rez['stats'] as $key => $stat) {
                        $stat->user_photo = User::getPhotoById($stat->user_id);
                    }
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
        return (!empty($id)) ? static::selectRaw('SUM(pts) pts, ROUND(SUM(pts)/COUNT(user.id),1) ppg, ROUND((SUM(pts)+SUM(reb)+SUM(ast)+SUM(stl)+SUM(bs)-(SUM(fgma_2)-SUM(fgma_1))-(SUM(ftma_2)-SUM(ftma_1))-SUM(`to`))/COUNT(user.id),0) eff')
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
            'pf',
            DB::raw('round(( fgma_1/fgma_2 * 100 )) as fg'),
            'fgma_1',
            'fgma_2',
            'pma_1',
            'pma_2',
            'player.team_id',
            'user.first_name',
            'user.last_name',
            'player.number',
            'player.id'
        ])->where('game_id', $game->id)->join('player', 'game_player_stat_basketball.player_id', 'player.id')
                          ->join('user', 'player.user_id', 'user.id')->get();

        $rez['total'] = static::getTotalPStats($rez['stats']);


        if ($opponentGame) {
            $rez['opponentStats'] = DB::table((new static)->getTable())->select([
                'pts',
                'pf',
                DB::raw('round(( fgma_1/fgma_2 * 100 )) as fg'),
                'fgma_1',
                'fgma_2',
                'pma_1',
                'pma_2',
                'player.team_id',
                'user.first_name',
                'user.last_name',
                'player.number',
                'player.id'

            ])->where('game_id', $opponentGame->id)
                                      ->join('player', 'game_player_stat_basketball.player_id', 'player.id')
                                      ->join('user', 'player.user_id', 'user.id')->get();

            $rez['opponentTotal'] = static::getTotalPStats($rez['opponentStats']);
        }

        return $rez;
    }

    public static function getTotalPStats($stats)
    {
        if ($stats->count()) {
            $rez = ['pts' => 0, 'pf' => 0, 'fg' => 0, 'fgma_1' => 0, 'fgma_2' => 0, 'pma_1' => 0, 'pma_2' => 0];
            foreach ($stats as $stat) {
                foreach ($rez as $key => $field) {
                    $rez[$key] += $stat->{$key};


                }
            }
            $rez['fg'] = round($rez['fg'] / count($rez));

            return $rez;
        }
    }


    public static function getTopGamePlayers($game, $limit)
    {

        $rez['columns'] = ['reb', 'pma_1', 'ast', 'stl', 'pts'];
        $rez['columnsName'] = ['REB', '3PT', 'AST', 'ST', 'PTS'];
        $opponentGame = $game->getOpponentGame();
        $rez['main'] = DB::table((new static)->getTable())->select([
            'game_id',
            'pts',
            'reb',
            'pma_1',
            'ast',
            'stl',
            'user.first_name',
            'user.last_name',
            'player.number',
            'player.id'
        ])->where('game_id', $game->id)->join('player', 'game_player_stat_basketball.player_id', 'player.id')
                         ->join('user', 'player.user_id', 'user.id')->orderBy('pts', 'desc')->limit($limit)->get();
        if ($opponentGame) {
            $rez['opponent'] = DB::table((new static)->getTable())->select([
                'game_id',
                'pts',
                'reb',
                'pma_1',
                'ast',
                'stl',
                'user.first_name',
                'user.last_name',
                'player.number',
                'player.id'

            ])->where('game_id', $opponentGame->id)
                                 ->join('player', 'game_player_stat_basketball.player_id', 'player.id')
                                 ->join('user', 'player.user_id', 'user.id')->orderBy('pts', 'desc')->limit($limit)
                                 ->get();


        }

        return $rez;
    }

    /**
     * @param $player_id
     * @return array
     */
    public function getTeamRosterStatistics($player_id)
    {
        $data = self::selectRaw('COUNT(player_id) as gp, 
                                ROUND(SUM(pts)/COUNT(player_id),1) pts, 
                                ROUND(SUM(reb)/COUNT(player_id),1) reb, 
                                ROUND(SUM(ast)/COUNT(player_id),1) ast, 
                                ROUND(SUM(stl)/COUNT(player_id),1) stl')->where(['player_id' => $player_id])
                    ->groupBY('player_id')->get()->toArray();

        if (empty($data)) {
            foreach (static::$rosterAttr as $key => $value) {
                $data += [$key => 0];
            }
            $data = [$data];
        }

        return $data;
    }

    /**
     * @param $pIds
     * @return mixed
     */
    public function getTotalStandings($pIds)
    {
        $rez = \DB::select('SELECT SUM(t1.fg) fg, 
                            SUM(t1.pf) pf, 
                            SUM(t1.pts) pts,
                            ROUND(SUM(t1.fgma_1)/SUM(t1.fgma_2)*100,0) fgm_pct, 
                            ROUND(SUM(t1.pma_1)/SUM(t1.pma_2)*100,0) 3pt_pct 
                            FROM (SELECT 
                            SUM(fgma_1) fg, 
                            SUM(fgma_1) fgma_1, 
                            SUM(fgma_2) fgma_2, 
                            SUM(pma_1) pma_1, 
                            SUM(pma_2) pma_2, 
                            SUM(pf) pf, 
                            SUM(pts) pts, 
                            ANY_VALUE(player_id) player_id, 
                            player.team_id team_id 
                            FROM ' . $this->getTable() . ' 
                            LEFT JOIN player ON player.id=player_id 
                            WHERE player_id IN(' . $pIds . ') 
                            GROUP BY player_id) AS t1 
                            GROUP BY t1.team_id');

        if (!empty($rez[0])) {
            return $rez[0];
        }
    }

    /**
     * @param $pIds
     * @param $limit
     * @return array
     */
    public function getPlayerStandings($pIds, $limit)
    {
        $query = ($limit != null) ? ' LIMIT ' . $limit : '';

        return \DB::select('SELECT
                COUNT(' . $this->getTable() . '.id) gp,
                SUM(fgma_1) fg,
                SUM(fgma_1) fgma_1,
                SUM(fgma_2) fgma_2,
                ROUND(SUM(fgma_1)/SUM(fgma_2)*100,0) fgm_pct,
                SUM(pma_1) pma_1,
                SUM(pma_2) pma_2, 
                ROUND(SUM(pma_1)/SUM(pma_2)*100,0) 3pt_pct,
                SUM(ftma_1) ftma_1,
                SUM(ftma_2) ftma_2,
                ROUND(SUM(ftma_1)/SUM(ftma_2)*100,0) ft_pct,
                SUM(pf) pf, 
                SUM(reb) reb, 
                SUM(ast) ast, 
                SUM(stl) stl, 
                SUM(`to`) `to`, 
                SUM(bs) bs, 
                SUM(pts) pts, 
                ANY_VALUE(player_id) player_id,
                player.user_id  user_id,
                player.team_id team_id,
                team.is_logo is_logo,
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
                WHERE player_id IN(' . $pIds . ')
                GROUP BY player_id ORDER BY pts DESC' . $query);
    }

    /**
     * @param $stats
     * @return mixed
     */
    public static function playerSeasonStats($stats)
    {
        $totalPts = $totalFgma1 = $totalFgma2 = $totalPma1 = $totalPma2 = $totalFtma1 = 0;
        $totalFtma2 = $totalAst = $totalTo = $totalReb = $totalBs = $totalStl = 0;
        $totals = count($stats);

        foreach ($stats as $num => $game) {
            $totalPts += $game->pts;
            $totalFgma1 += $game->fgma_1;
            $totalFgma2 += $game->fgma_2;
            $totalPma1 += $game->pma_1;
            $totalPma2 += $game->pma_2;
            $totalFtma1 += $game->ftma_1;
            $totalFtma2 += $game->ftma_2;
            $totalAst += $game->ast;
            $totalTo += $game->to;
            $totalReb += $game->reb;
            $totalBs += $game->bs;
            $totalStl += $game->stl;

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
                    'pts' => $game->pts,
                    'fgm' => $game->fgma_1 . '/' . $game->fgma_2,
                    'fg' => (!empty($game->fgma_2) && !empty($game->fgma_1)) ? intval(($game->fgma_1 / $game->fgma_2) * 100) . '%' : 0,
                    'ptm' => $game->pma_1 . '/' . $game->pma_2,
                    'pt' => (!empty($game->pma_2) && !empty($game->pma_1)) ? intval(($game->pma_1 / $game->pma_2) * 100) . '%' : 0,
                    'ftm' => $game->ftma_1 . '/' . $game->ftma_2,
                    'ft' => (!empty($game->ftma_2) && !empty($game->ftma_1)) ? intval(($game->ftma_1 / $game->ftma_2) * 100) . '%' : 0,
                    'ast' => $game->ast,
                    'to' => $game->to,
                    'reb' => $game->reb,
                    'blk' => $game->bs,
                    'stl' => $game->stl,
                ]
            ];
        }

        if (count($stats) != 0) {
            $pma1APG = $totalPma1 / count($stats);
            $pma2APG = $totalPma2 / count($stats);
            $fgma1APG = $totalFgma1 / count($stats);
            $fgma2APG = $totalFgma2 / count($stats);
            $ftma1APG = $totalFtma1 / count($stats);
            $ftma2APG = $totalFtma2 / count($stats);

            $dataApg = [
                'gp' => count($stats),
                'pts' => (!empty($totalPts)) ? number_format($totalPts / count($stats), 1) : 0,
                'fgm' => number_format($fgma1APG, 1) . '/' . number_format($fgma2APG, 1),
                'fg' => (!empty($fgma2APG)) ? intval(($fgma1APG / $fgma2APG) * 100) . '%' : 0,
                'ptm' => number_format($pma1APG, 1) . '/' . number_format($pma2APG, 1),
                'pt' => (!empty($pma2APG)) ? intval(($pma1APG / $pma2APG) * 100) . '%' : 0,
                'ftm' => number_format($ftma1APG, 1) . '/' . number_format($ftma2APG, 1),
                'ft' => (!empty($ftma2APG)) ? intval(($ftma1APG / $ftma2APG) * 100) . '%' : 0,
                'ast' => (!empty($totalAst)) ? number_format($totalAst / count($stats), 1) : 0,
                'to' => (!empty($totalTo)) ? number_format($totalTo / count($stats), 1) : 0,
                'reb' => (!empty($totalReb)) ? number_format($totalReb / count($stats), 1) : 0,
                'blk' => (!empty($totalBs)) ? number_format($totalBs / count($stats), 1) : 0,
                'stl' => (!empty($totalStl)) ? number_format($totalStl / count($stats), 1) : 0,
            ];
            $dataTotal = [
                'gp' => count($stats),
                'pts' => $totalPts,
                'fgm' => $totalFgma1 . '/' . $totalFgma2,
                'fg' => (!empty($totalFgma2)) ? intval(($totalFgma1 / $totalFgma2) * 100) . '%' : 0,
                'ptm' => $totalPma1 . '/' . $totalPma2,
                'pt' => (!empty($totalPma2)) ? intval(($totalPma1 / $totalPma2) * 100) . '%' : 0,
                'ftm' => $totalFtma1 . '/' . $totalFtma2,
                'ft' => (!empty($totalFtma2)) ? intval(($totalFtma1 / $totalFtma2) * 100) . '%' : 0,
                'ast' => $totalAst,
                'to' => $totalTo,
                'reb' => $totalReb,
                'blk' => $totalBs,
                'stl' => $totalStl,
            ];
        }

        $data['stats'] = (isset($dataStat)) ? $dataStat : ['game' => [], 'data' => []];
        $data['apg'] = (isset($dataApg)) ? $dataApg : [];
        $data['total'] = (isset($dataTotal)) ? $dataTotal : [];

        return $data;
    }

    /**
     * @param $statData
     * @return array
     */
    public static function personalResultStats($statData)
    {
        $data = $dataStat = [];
        $total_games = $total_fgma_1 = $total_fgma_2 = $total_pma_1 = $total_pma_2 = $total_ftma_1 = $total_ftma_2 = 0;
        $total_stl = $total_to = $total_bs = $total_pts = $total_reb = $total_ast = $total_pf = 0;
        $total_fgm_pct = $total_3pt_pct = $total_ft_pct = 0;
        foreach ($statData as $stat) {
            $total_fgma_1 += $stat->fgma_1;
            $total_fgma_2 += $stat->fgma_2;
            $total_fgm_pct += $stat->fgm_pct;
            $total_pma_1 += $stat->pma_1;
            $total_pma_2 += $stat->pma_2;
            $total_3pt_pct += $stat->{'3pt_pct'};
            $total_ftma_1 += $stat->ftma_1;
            $total_ftma_2 += $stat->ftma_2;
            $total_ft_pct += $stat->ft_pct;
            $total_reb += $stat->reb;
            $total_ast += $stat->ast;
            $total_pf += $stat->pf;
            $total_stl += $stat->stl;
            $total_to += $stat->to;
            $total_bs += $stat->bs;
            $total_pts += $stat->pts;
            $total_games += $stat->gp;

            $dataStat[] = [
                'season' => $stat->season,
                'gp' => $stat->gp,
                'fgma' => $stat->fgma_1 . '-' . $stat->fgma_2,
                'fg' => (!empty($stat->fgm_pct)) ? $stat->fgm_pct . '%' : '-',
                'pma' => $stat->pma_1 . '-' . $stat->pma_1,
                'pm' => (!empty($stat->{'3pt_pct'})) ? $stat->{'3pt_pct'} . '%' : '-',
                'ftma' => $stat->ftma_1 . '-' . $stat->ftma_2,
                'ft' => (!empty($stat->ft_pct)) ? $stat->ft_pct . '%' : '-',
                'reb' => $stat->reb,
                'ast' => $stat->ast,
                'pf' => $stat->pf,
                'st' => $stat->stl,
                'to' => $stat->to,
                'bs' => $stat->bs,
                'pts' => $stat->pts,
            ];
        }

        if (count($dataStat) > 1) {
            $dataTotal = [
                'gp' => $total_games,
                'fgma' => $total_fgma_1 . '-' . $total_fgma_2,
                'fg' => (count($dataStat)) ? number_format($total_fgm_pct / count($dataStat), 2) . '%' : '-',
                'pma' => $total_pma_1 . '-' . $total_pma_1,
                'pm' => (count($dataStat)) ? number_format($total_3pt_pct / count($dataStat), 2) . '%' : '-',
                'ftma' => $total_ftma_1 . '-' . $total_ftma_2,
                'ft' => (count($dataStat)) ? number_format($total_ft_pct / count($dataStat), 2) . '%' : '-',
                'reb' => $total_reb,
                'ast' => $total_ast,
                'pf' => $total_pf,
                'st' => $total_stl,
                'to' => $total_to,
                'bs' => $total_bs,
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
                    'gp' => $stat->gp,
                    'fgma' => $stat->fgma_1 . '-' . $stat->fgma_2,
                    'fg' => (!empty($stat->fgm_pct)) ? $stat->fgm_pct . '%' : '-',
                    'pma' => $stat->pma_1 . '-' . $stat->pma_2,
                    'pm' => (!empty($stat->{'3pt_pct'})) ? $stat->{'3pt_pct'} . '%' : '-',
                    'ftma' => $stat->ftma_1 . '-' . $stat->ftma_2,
                    'ft' => (!empty($stat->ft_pct)) ? $stat->ft_pct . '%' : '-',
                    'reb' => $stat->reb,
                    'ast' => $stat->ast,
                    'pf' => $stat->pf,
                    'st' => $stat->stl,
                    'to' => $stat->to,
                    'bs' => $stat->bs,
                    'pts' => $stat->pts,
                ]
            ];
        }

        return $dataStat;
    }
}
