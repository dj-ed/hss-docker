<?php

namespace App\Transformers;

use App\Models\PlayerMetrics;
use League\Fractal\TransformerAbstract;

class PlayerTransformer extends TransformerAbstract
{
    public function transform($player)
    {
        $teamPlayers = [];
        if ($player->team && $player->team->players) {
            $teamPlayers = $player->team->players()->with('user')->get()->map(function ($teamPlayer) {
                return [
                    'id' => $teamPlayer->id,
                    'number' => $teamPlayer->number,
                    'name' => $teamPlayer->user->first_name . ' ' . $teamPlayer->user->last_name,
                    'userPhotoUrl' => $teamPlayer->user->getPhoto(),
                ];
            });
        }

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
            'metrics' => $this->metrics($player->sizeSystem, $player),
            'position' => $player->getPositions(),
            'positionShort' => $player->getPositions(true),
            'teamId' => $player->team->id,
            'social' => $socials,
            'teamPlayers' => $teamPlayers,
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