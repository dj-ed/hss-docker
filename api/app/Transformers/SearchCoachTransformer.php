<?php

namespace App\Transformers;

use App\Http\Controllers\Api\SearchController;
use App\Models\Favorite;
use App\Models\Sports;
use League\Fractal\TransformerAbstract;

class SearchCoachTransformer extends TransformerAbstract
{
    public function transform($coach)
    {
        return [
            'id' => $coach->id,
            'userPhotoUrl' => $coach->user->getPhoto(),
            'schoolLogo' => $coach->school->getSchoolLogo(),
            'schoolId' => $coach->school_id,
            'schoolName' => $coach->school->name,
            'schoolShortName' => $coach->school->getShortName(),
            'coachType' => $coach->coach_type,
            'name' => $coach->user->first_name . ' ' . $coach->user->last_name,
            'sport' => Sports::getSportName($coach->user->sport_id)
        ];
    }
}