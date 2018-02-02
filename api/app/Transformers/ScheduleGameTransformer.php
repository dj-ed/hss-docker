<?php

namespace App\Transformers;

use App\Http\Controllers\Api\SearchController;
use App\Models\Favorite;
use App\Models\Team;
use League\Fractal\TransformerAbstract;

class ScheduleGameTransformer extends TransformerAbstract
{
    public function transform($game)
    {
        return $this->innerTransform($game);
    }

    public function innerTransform($game)
    {
        return [
            'id' => $game->id,
            'date' => $game->date,
            'dateTime' => $game->date_time,
            'gameType' => $game->getShortGameType(),
            'where' => $game->where,
            'win' => $game->win,
            'team' => $this->teamTransform($game->team, $game),
            'opponentTeam' => $this->teamTransform($game->opponentTeam, $game),
            'scoreTeam' => $game->score_team,
            'scoreOpponent' => $game->score_opponent,
            'sportId'=>$game->team->sport_id,
            'location'=>$game->game_address
        ];
    }

    public function teamTransform($team, $game)
    {
        if (!empty($team)) {
            return [
                'id' => $team->id,
                'name'=>$team->name,
                'shortName' => Team::generateShortName($team->name),
                'logoUrl' => $team->getLogo(),
            ];
        } else {
            return [
                'name'=>$game->opponent_team_name,
                'shortName' => Team::generateShortName($game->opponent_team_name),
                'logoUrl' => 'default.png' // TODO: default logo
            ];
        }
    }
}