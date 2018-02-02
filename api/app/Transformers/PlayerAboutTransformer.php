<?php

namespace App\Transformers;

use App\Models\PlayerInfo;
use App\Models\PlayerMetrics;
use League\Fractal\TransformerAbstract;
use Storage;

class PlayerAboutTransformer extends TransformerAbstract
{
    public function transform($player)
    {
        $transcript = null;
        if ($player->metrics->transcripts == 1) {
            $transcript = Storage::disk('s3')->url('uploads/players-transcripts/'.$player->metrics->id.'.pdf');
        }

        $graduates = null;
        if ($player->guard < 5) {
            $season = explode('-', $player->team->season->title_short);
            $minus = 5 - $player->guard;
            $graduates = $season[0] + $minus;
        }

        return [
            'metrics' => [
                'height' => PlayerMetrics::getFormatHeightMetric($player->sizeSystem, $player->metrics->height, $player->metrics->height_in),
                'weight' => PlayerMetrics::getFormatWeightMetric($player->sizeSystem, $player->metrics->weight),
                'position' => $player->getPositions(),
                'wingspan' => PlayerMetrics::getFormatDistanceMetric($player->sizeSystem, $player->metrics->wingspan),
                'totalReach' => PlayerMetrics::getFormatDistanceMetric($player->sizeSystem, $player->metrics->total_reach),
                'shoeSize' => $player->metrics->shoe_size,
                'hearing' => $player->metrics->hearing,
                'eyesight' => $player->metrics->eyesight,
                'dominantHand' => $player->metrics->dominant_hand,
                'maxVerticalJump' => PlayerMetrics::getFormatDistanceMetric($player->sizeSystem, $player->metrics->vertical_jump),
                'squatMax' => PlayerMetrics::getFormatWeightMetric($player->sizeSystem, $player->metrics->squat_max),
                'courtSprint' => $player->metrics->court_sprint,
                'laneShuttle' => $player->metrics->lane_shuttle,
                'laneAgilitySpeed' => $player->metrics->lane_agility_speed,
                'noStepVertical' => PlayerMetrics::getFormatDistanceMetric($player->sizeSystem, $player->metrics->no_step_vert),
                'benchPress' => PlayerMetrics::getFormatWeightMetric($player->sizeSystem, $player->metrics->bench_press_140lbs),
                'maxBenchPress' => PlayerMetrics::getFormatWeightMetric($player->sizeSystem, $player->metrics->bench_press_max),
                'mileRunTime' => PlayerMetrics::getFormatDistanceMetric($player->sizeSystem, round($player->metrics->mile_run_time * 0.3048)),
                'graduates' => $graduates,
                'study' => config('params.guard.'.$player->guard),
                'genderName' => $player->team->teamGender->title,
                'teamType' => $player->team->teamType->title,
                'gpa' => $player->metrics->gpa,
                'sat' => $player->metrics->sat,
                'act' => $player->metrics->act,
                'transcript' => $transcript,
            ],
            'summary' => [
                'bio' => $player->info->bio,
                'favoriteQuote' => $player->info->favorite_quote,
                'offTheCourt' =>$player->info->getOffTheCourt(PlayerInfo::$offTheCourt)
            ]
        ];
    }
}