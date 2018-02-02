<?php

namespace App\Transformers;

use App\Models\Team;
use League\Fractal\TransformerAbstract;

class TeamShortTransformer extends TransformerAbstract
{
    public function transform(Team $team)
    {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'nameShort' => Team::generateShortName($team->name),
            'logoUrl' => $team->getLogo(),
            'type' => $team->getTeamTypeFull(),
        ];
    }
}