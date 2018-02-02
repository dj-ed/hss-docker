<?php

namespace App\Transformers;

use App\Models\Globals\City;
use League\Fractal\TransformerAbstract;

class LocationCitiesTransformer extends TransformerAbstract
{

    public function transform(City $city)
    {
        return [
            'id' => $city->id,
            'name' => $city->name,
            'stateId' => $city->state_id,
        ];
    }

}