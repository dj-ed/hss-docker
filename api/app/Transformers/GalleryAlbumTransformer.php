<?php

namespace App\Transformers;

use App\Models\UserContent;
use App\Models\UserContentAlbum;
use League\Fractal\TransformerAbstract;

class GalleryAlbumTransformer extends TransformerAbstract
{
    public function transform($media)
    {
        return [
            'id' => $media->id,
            'title' => $media->title,
            'descriptions' => $media->description,
            'mediaType' => $media->media_type,
            'date' => strtotime($media->date) * 1000,
            'mediaUrl' => (!empty($media->source_link)) ? $media->source_link : UserContent::getMediaSourceLink($media->id, $media->media_type),
            'likesCommentsCount' => UserContent::getLikesAndCommentsCount($media->id),
            'gameId' => ($media->game_id == null) ? '00' : $media->game_id,
            'gameData' => ($media->game_id == null) ? [] : UserContent::getGame($media->game_id),
        ];
    }
}