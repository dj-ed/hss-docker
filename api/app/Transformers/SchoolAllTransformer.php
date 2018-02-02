<?php

namespace App\Transformers;

use App\Models\School;
use League\Fractal\TransformerAbstract;

class SchoolAllTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'socials'
    ];

    public function transform(School $school)
    {
        return [
            'id' => $school->id,
            'name' => $school->name,
            'logoUrl' => $school->getSchoolLogo(),
            'state' => $school->stateName,
            'stateShortName' => $school->stateShortName,
            'county' => $school->countyName,
            'sports' => $school->sports->pluck('sport_id'),
            'teamCount' => $school->teams->count(),
            'principal' => $school->principal
        ];
    }

    public function includeSocials(School $school)
    {
        return $this->item($school->socials, new SchoolSocialTransformer);
    }
}