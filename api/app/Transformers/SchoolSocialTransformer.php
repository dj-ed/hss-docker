<?php

namespace App\Transformers;

use App\Models\Globals\States;
use App\Models\OtherPersonInfo;
use App\Models\School;
use App\Models\SchoolPerson;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class SchoolSocialTransformer extends TransformerAbstract
{
    public function transform($social)
    {
        return [
            'facebook' => !empty($social->fb_show) ? $social->fb_link : null,
            'twitter' => !empty($social->tw_show) ? $social->tw_link : null,
            'instagram' => !empty($social->ins_show) ? $social->ins_link : null,
            'pinterest' => !empty($social->pin_show) ? $social->pin_link : null,
            'youtube' => !empty($social->youtube_show) ? $social->youtube_link : null,
            'vimeo' => !empty($social->vimeo_show) ? $social->vimeo_link : null,
        ];
    }

}