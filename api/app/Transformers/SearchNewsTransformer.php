<?php

namespace App\Transformers;

use App\Http\Controllers\Api\SearchController;
use App\Models\News;
use App\Models\ScrapBook;
use League\Fractal\TransformerAbstract;

class SearchNewsTransformer extends TransformerAbstract
{

    public function transform(News $news)
    {
        $coverMedia = $news->media->filter(function ($value) {
            return $value->is_cover == 1;
        })->first();

        return [
            'id' => $news->id,
            'title' => $news->title,
            'date' => strtotime($news->created_at) * 1000,
            'slug' => $news->slug,
            'likes' => $news->likes->count(),
            'comments' => $news->comments->count(),
            'thumbUrl' => $coverMedia->thumb_url,
            'source' => $news->external_source,
            'authorName' => $news->getAuthors(),
            'description' => substr(strip_tags($news->text), 0, 250) . '...',
        ];
    }

}