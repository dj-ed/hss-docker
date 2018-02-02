<?php

namespace App\Transformers;

use App\Models\Globals\Counties;
use App\Models\Globals\States;
use App\Models\School;
use App\Models\Sports;
use League\Fractal\TransformerAbstract;

class SearchSchoolTransformer extends TransformerAbstract
{
    public function transform(School $school)
    {
        return [
            'id' => $school->id,
            'name' => $school->name,
            'fullName' => $school->full_name,
            'logoUrl' => $school->getSchoolLogo(),
            'state' => States::getNameById($school->state_id),
            'county' => Counties::getNameById($school->county_id),
            'sports' => Sports::getSports($school->sports->pluck('sport_id'))
        ];
    }
}