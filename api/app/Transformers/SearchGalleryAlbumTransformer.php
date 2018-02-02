<?php

namespace App\Transformers;

use App\Models\UserContent;
use League\Fractal\TransformerAbstract;

class SearchGalleryAlbumTransformer extends TransformerAbstract
{
    public function transform($media)
    {
        return [
            'id' => $media->id,
            'title' => $media->mainImage->title,
            'descriptions' => $media->mainImage->description,
            'mediaType' => $media->mainImage->media_type,
            'date' => strtotime($media->mainImage->posted_date) * 1000,
            'isIframe' => (!empty($media->mainImage->source_link)) ? true : false,
            'mediaUrl' => (!empty($media->mainImage->source_link)) ? $media->mainImage->source_link : UserContent::getMediaSourceLink($media->mainImage->id, $media->mainImage->media_type),
            'likesCommentsCount' => UserContent::getLikesAndCommentsCount($media->id),
            'gameId' => ($media->game_id == null) ? '00' : $media->game_id,
            'gameData' => ($media->game_id == null) ? [] : UserContent::getGame($media->game_id),
            'countPhoto' => UserContent::getCountPhotoOrVideo($media->id, 'photo'),
            'countVideo' => UserContent::getCountPhotoOrVideo($media->id, 'video')
        ];
    }
}