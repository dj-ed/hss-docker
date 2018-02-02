<?php

namespace App\Transformers\News;

use League\Fractal\TransformerAbstract;

class NewsMediaTransformer extends TransformerAbstract
{
    public function transform($media)
    {
        return [
            'id' => $media->id,
            'title' => $media->title,
            'source' => $media->source,
            'size' => $media->size,
            'location' => $media->location,
            'type' => $media->type,
            'isCover' => $media->is_cover,
            'fileUrl' => $media->file_url,
            'thumbUrl' => $media->thumb_url,
            'sortOrder' => $media->sort_order,
        ];
    }

}