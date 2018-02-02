<?php

namespace App\Transformers;

use App\Models\Player;
use App\Models\PlayerMetrics;
use App\Models\Sports;
use League\Fractal\TransformerAbstract;

class FavoritesPlayerTransformer extends TransformerAbstract
{
    public function transform(Player $player)
    {
        $socials = [
            'fb' => '???',
            'tw' => '???',
            'ins' => '???',
            'gp' => '???',
        ];

        return [
            'id' => $player->id,
            'userPhotoUrl' => $player->user->getPhoto(),
            'logo' => $player->team->getLogo(),
            'name' => $player->user->first_name . ' ' . $player->user->last_name,
            'number' => $player->number,
            'guard' => $player->getGuard(),
            'guardShort' => $player->getGuard(true),
            'position' => $player->getPositions(),
            'positionShort' => $player->getPositions(true),
            'metrics' => $this->metrics($player->sizeSystem, $player),
            'teamId' => $player->team->id,
            'social' => $socials,
            'sport' => Sports::getSports([$player->team->sport_id])->first(),
            'teamType' => $player->team->teamType->title,
            'teamTypeLogo' => ($player->team->gender_id == 1) ? '/img/male.svg' : '/img/female.svg',
            'genderId' => $player->team->gender_id,
            'genderName' => $player->team->teamGender->title,
            'leagues' => $player->team->teamGender->title . ' ' . $player->team->teamType->title,
        ];
    }

    public function metrics($sizeSystem, $player)
    {
        $result = [
            'height' => $player->metrics->height,
            'height_in' => $player->metrics->height_in,
            'weight' => $player->metrics->weight
        ];

        if ($sizeSystem == 'metric') {
            $result = PlayerMetrics::convertToMetric($result);
        }

        $data = [];
        foreach ($result as $key => $value) {
            if ($key == 'height' || $key == 'height_in') {
                $data[$key] = PlayerMetrics::getFormatHeightMetric($sizeSystem, ($key == 'height') ? $value : '', ($key == 'height_in') ? $value : '');
            } else {
                $data[$key] = PlayerMetrics::getFormatWeightMetric($sizeSystem, $value);
            }
        }

        return $data;
    }
}