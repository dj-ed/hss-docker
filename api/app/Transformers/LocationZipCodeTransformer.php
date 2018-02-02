<?php

namespace App\Transformers;

use App\Models\Globals\ZipCode;
use League\Fractal\TransformerAbstract;

class LocationZipCodeTransformer extends TransformerAbstract
{
    public function transform(ZipCode $zipCode)
    {
        return [
            'zip' => $zipCode->zip,
            'cityId' => $zipCode->city_id,
            'stateId' => $zipCode->city->state_id
        ];
    }
}