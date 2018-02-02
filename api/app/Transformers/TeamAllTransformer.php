<?php

namespace App\Transformers;

use App\Models\Team;
use App\Models\TeamCoach;
use League\Fractal\TransformerAbstract;

class TeamAllTransformer extends TransformerAbstract
{
    public function transform(Team $team)
    {
        $teamSocials = [
            'fb' => '???',
            'tw' => '???',
            'ins' => '???',
            'pin' => '???',
            'youtube' => '???',
            'vimeo' => '???'
        ];

        return [
            'id' => $team->id,
            'name' => $team->name,
            'logoUrl' => $team->getLogo(),
            'teamType' => $team->teamType->title,
            'teamTypeLogo' => ($team->gender_id == 1) ? '/img/male.svg' : '/img/female.svg',
            'genderId' => $team->gender_id,
            'genderName' => $team->teamGender->title,
            'sportId' => $team->sport_id,
            'leagues' => $team->teamGender->title . ' ' . $team->teamType->title,
            'social' => $teamSocials,
            'coaches'=> TeamCoach::teamCoach($team->id)
        ];
    }
}