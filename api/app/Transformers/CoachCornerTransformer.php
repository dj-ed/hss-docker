<?php

namespace App\Transformers;

use App\Http\Controllers\Api\TeamController;
use App\Models\Favorite;
use League\Fractal\TransformerAbstract;

class CoachCornerTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'articles'
    ];

    public function transform($coach)
    {
        $socials = [
            'fb' => '???',
            'tw' => '???',
            'ins' => '???',
            'gp' => '???',
        ];

        return [
            'id' => $coach->user_id,
            'name' => $coach->user->first_name . ' ' . $coach->user->last_name,
            'bio' => $coach->bio,
            'userPhotoUrl' => $coach->user->getPhoto(),
            'social' => $socials
        ];
    }

    public function includeArticles($coach)
    {
        return $this->collection($coach->coachesCorners, new CoachCornerArticlesTransformer);
    }


}