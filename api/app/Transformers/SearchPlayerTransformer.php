<?php

namespace App\Transformers;

use App\Http\Controllers\Api\SearchController;
use App\Models\Favorite;
use App\Models\Game;
use App\Models\Sports;
use League\Fractal\TransformerAbstract;

class SearchPlayerTransformer extends TransformerAbstract
{
    public function transform($player)
    {
        return [
            'id' => $player->id,
            'userPhotoUrl' => $player->user->getPhoto(),
            'logo' => $player->team->getLogo(),
            'name' => $player->user->first_name . ' ' . $player->user->last_name,
            'number' => $player->number,
            'teamId' => $player->team_id,
            'stats' => $this->getStats($player->team, $player->id)
        ];
    }

    public function getStats($team, $playerId)
    {
        $sport = Sports::getSportGameStats($team->sport_id);
        $sportClass = (new $sport['sportClass']);
        $data['columns'] = Game::getColumnsData($sportClass::$searchPlayerAttr);
        $data['data'] = $sportClass->getSearchPlayerStats($playerId);
        return $data;
    }
}