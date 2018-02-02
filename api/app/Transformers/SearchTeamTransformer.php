<?php

namespace App\Transformers;

use App\Http\Controllers\Api\SearchController;
use App\Models\Favorite;
use App\Models\Game;
use App\Models\Player;
use App\Models\Sports;
use App\Models\Team;
use League\Fractal\TransformerAbstract;

class SearchTeamTransformer extends TransformerAbstract
{
    public function transform($team)
    {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'logoUrl' => Team::getLogoById($team->id, $team->is_logo, $team->use_school_logo),
            'district' => $team->district,
            'countyName' => $team->countyName,
            'stats' => $this->getStats($team)
        ];
    }

    public function getStats($team)
    {
        $sport = Sports::getSportGameStats($team->sport_id);
        $pIds = Player::getPlayersList(['teamId' => $team->id]);
        $sportClass = (new $sport['sportClass']);
        $pIds = $pIds->implode(',');
        $data['columns'] = Game::getColumnsData($sportClass::$searchTeamAttr);
        $data['data'] = (!empty($pIds)) ? $sportClass->getTotalStandings($pIds) : [];
        return $data;
    }
}