<?php

namespace App\Transformers\News;

use App\Models\News;
use League\Fractal\TransformerAbstract;

class NewsTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'media', 'contributors'
    ];

    public function transform(News $news)
    {
        return [
            'id' => $news->id,
            'title' => $news->title,
            'date' => strtotime($news->created_at) * 1000,
            'source' => $news->external_source,
            'text' => $news->text,
            'tags' => $news->tags,
            'slug' => $news->slug,
            'likes' => $news->likes->count(),
            'comments' => $news->comments->count(),
            'sport' => strtolower($news->sport->title),
            'gender' => ($news->gender==1)? 'boys' : 'girls',
        ];
    }

    public function includeMedia(News $news)
    {
        return $this->collection($news->media, new NewsMediaTransformer);
    }

    public function includeContributors(News $news)
    {
        return $this->collection($news->contributors, new NewsContributorTransformer);
    }

}