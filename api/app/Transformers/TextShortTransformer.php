<?php

namespace App\Transformers;

use App\Models\Texts;
use League\Fractal\TransformerAbstract;

class TextShortTransformer extends TransformerAbstract
{
    public function transform(Texts $text)
    {
        return [
            'url' => $text->url,
            'title' => $text->title,
        ];
    }
}