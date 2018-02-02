<?php

namespace App\Transformers;

use App\Models\Team;
use League\Fractal\TransformerAbstract;

class UpcomingGameTransformer extends TransformerAbstract
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
            'team' => $this->teamTransform($game->team, $game),
            'opponentTeam' => $this->teamTransform($game->opponentTeam, $game),
            'sportId' => $game->sport_id
        ];
    }

    public function teamTransform($team, $game)
    {
        if (!empty($team)) {
            return [
                'id' => $team->id,
                'shortName' => Team::generateShortName($team->name),
                'logoUrl' => $team->getLogo()
            ];
        } else {
            return [
                'shortName' => Team::generateShortName($game->opponent_team_name),
                'logoUrl' => 'default.png' //TODO: default logo
            ];
        }
    }
}