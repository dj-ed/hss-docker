<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class CoachCornerArticlesTransformer extends TransformerAbstract
{
    public function transform($article)
    {
        return [
            'id' => $article->id,
            'title'=>$article->title,
            'description'=>$article->description,
            'date' => strtotime($article->date) * 1000,
            'author' => $article->teamCoach->user->first_name .' '. $article->teamCoach->user->last_name
        ];
    }
}