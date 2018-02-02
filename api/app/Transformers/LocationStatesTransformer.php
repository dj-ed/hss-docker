<?php

namespace App\Transformers;

use App\Models\Globals\States;
use League\Fractal\TransformerAbstract;

class LocationStatesTransformer extends TransformerAbstract
{

    public function transform(States $state)
    {
        return [
            'id' => $state->id,
            'name' => $state->name,
            'abbr' => $state->abbr,
            'icon' => States::getFlagLogo($state->name),
        ];
    }

}