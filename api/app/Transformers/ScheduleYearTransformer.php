<?php

namespace App\Transformers;

use App\Models\Game;
use League\Fractal\TransformerAbstract;

class ScheduleYearTransformer extends TransformerAbstract
{
    public function transform(Game $game)
    {
        return [
            'id' => $game->id,
            'date' => $game->date,
            'gameType' => $game->getShortGameType(),
            'sportId'=>$game->sport_id,
            'count'=>$game->count
        ];
    }
}