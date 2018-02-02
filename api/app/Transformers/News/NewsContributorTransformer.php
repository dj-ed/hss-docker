<?php

namespace App\Transformers\News;

use League\Fractal\TransformerAbstract;

class NewsContributorTransformer extends TransformerAbstract
{
    public function transform($contributor)
    {
        return [
            'email'=> $contributor->email,
            'name'=>$contributor->name,
            'showEmail'=>$contributor->show_email
        ];
    }

}