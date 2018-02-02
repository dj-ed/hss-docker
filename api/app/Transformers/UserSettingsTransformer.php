<?php

namespace App\Transformers;

use App\Models\Globals\City;
use App\Models\Globals\States;
use App\Models\School;
use App\Models\Sports;
use App\Models\Team;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserSettingsTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $sports = $user->userAccesses->map(function ($sport) {
            if ($sport->sport_id != null) {
                return $this->sport($sport->sport);
            } else {
                return null;
            }
        });

        return [
            'startPage' => $user->start_page_url,
            'personalInfo' => [
                'email' => $user->email,
                'password' => '****',
                'state' => $this->state($user->state),
                'city' => $this->city($user->city),
                'hometownTeam' => $this->hometownTeam($user->hometownTeam),
                'zipCode' => $user->zip_code,
                'type' => $user->type->title,
                'sports' => ($sports[0] != null) ? $sports : Sports::all()->map(function ($sport) {
                    return $this->sport($sport);
                })
            ]
        ];
    }

    public function state(States $state)
    {
        return [
            'id' => $state->id,
            'name' => $state->name,
            'abbr' => $state->abbr,
        ];
    }

    public function city(City $city)
    {
        return [
            'id' => $city->id,
            'name' => $city->name,
            'abbr' => School::transformShortName($city->name)
        ];
    }

    public function hometownTeam(Team $hometownTeam)
    {
        return [
            'id' => $hometownTeam->id,
            'logo' => $hometownTeam->getLogo(),
            'name' => $hometownTeam->name,
        ];
    }

    public function sport(Sports $sport)
    {
        return [
            'id' => $sport->id,
            'title' => $sport->title,
            'logoUrl' => '/img/sports/' . strtolower($sport->title) . '.svg'
        ];
    }
}