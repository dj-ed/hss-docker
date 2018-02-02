<?php

namespace App\Transformers;

use App\Models\School;
use App\Models\Team;
use League\Fractal\TransformerAbstract;

class LocationTeamsTransformer extends TransformerAbstract
{

    public function transform(Team $team)
    {
        return [
            'schoolId' => $team->schoolId,
            'schoolName' => $team->schoolName,
            'schoolShortName' => School::getShortNameStatic($team->schoolName),
            'schoolLogo' => School::getSchoolLogoById($team->schoolIsLogo, $team->schoolId),
            'teamId' => $team->id,
            'teamName' => $team->name,
            'sportId'=>$team->sport_id,
            'genderName' => $team->genderName,
            'varsityName' => $team->varsityName,
            'varsityFullName' => $team->varsityFullName,
            'stateId' => $team->state_id,
            'cityId' => $team->city_id
        ];
    }

}