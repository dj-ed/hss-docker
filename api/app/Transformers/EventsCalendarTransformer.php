<?php

namespace App\Transformers;

use App\Models\Event;
use App\Models\Game;
use App\Models\Team;
use League\Fractal\TransformerAbstract;

class EventsCalendarTransformer extends TransformerAbstract
{
    public function transform(Event $event)
    {
        return [
            'id' => $event->game->id,
            'date' => $event->game->date,
            'dateTime' => $event->game->date_time,
            'gameType' => $event->game->getShortGameType(),
            'where' => $event->game->where,
            'win' => $event->game->win,
            'team' => $this->teamTransform($event->game->team, $event->game),
            'opponentTeam' => $this->teamTransform($event->game->opponentTeam, $event->game),
            'scoreTeam' => $event->game->score_team,
            'scoreOpponent' => $event->game->score_opponent,
            'sportId' => $event->game->team->sport_id,
            'location' => $event->game->game_address
        ];
    }

    public function teamTransform(Team $team, Game $game)
    {
        if (!empty($team)) {
            return [
                'id' => $team->id,
                'name' => $team->name,
                'shortName' => Team::generateShortName($team->name),
                'logoUrl' => $team->getLogo(),
            ];
        } else {
            return [
                'name' => $game->opponent_team_name,
                'shortName' => Team::generateShortName($game->opponent_team_name),
                'logoUrl' => 'default.png' // TODO: default logo
            ];
        }
    }
}