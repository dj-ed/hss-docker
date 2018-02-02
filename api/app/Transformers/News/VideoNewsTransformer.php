<?php

namespace App\Transformers\News;

use App\Models\News;
use League\Fractal\TransformerAbstract;

class VideoNewsTransformer extends TransformerAbstract
{

    public function transform(News $news)
    {
        $coverMedia = $news->media->filter(function ($value) {
            return $value->type == 'video' || $value->type == 'iframe';
        })->first();



        return [
            'id' => $news->id,
            'title' => $news->title,
            'date' => strtotime($news->created_at) * 1000,
            'slug' => $news->slug,
            'likes' => $news->likes->count(),
            'comments' => $news->comments->count(),
            'videoUrl' => $coverMedia->file_url,
            'videoType' => $coverMedia->type,
            'source' => $news->external_source,
            'authorName' => $news->getAuthors(),
            'description' => substr(strip_tags($news->text), 0, 350) . '...',
        ];
    }

}