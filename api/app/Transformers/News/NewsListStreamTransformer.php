<?php

namespace App\Transformers\News;

use App\Models\News;
use League\Fractal\TransformerAbstract;

class NewsListStreamTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'media'
    ];

    public function transform(News $news)
    {
        $tags = $news->tags->map(function ($tag) {
            return $tag->title;
        });

        return [
            'id' => $news->id,
            'title' => $news->title,
            'date' => strtotime($news->created_at) * 1000,
            'slug' => $news->slug,
            'likes' => $news->likes->count(),
            'comments' => $news->comments->count(),
            'source' => $news->external_source,
            'authorName' => $news->getAuthors(),
            'text' => $news->text,
            'tags' => $tags
        ];
    }

    public function includeMedia(News $news)
    {
        return $this->collection($news->media, new NewsMediaTransformer);
    }

}