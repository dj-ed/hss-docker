<?php

use Codeception\Util\HttpCode;

class ViewAlbumCest
{
    /**
     * @param ApiTester $I
     */
    public function _before(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     */
    public function _after(ApiTester $I)
    {
    }

    /**
     * @param ApiTester $I
     */
    public function badMethod(ApiTester $I)
    {
        $I->sendGET('gallery/view-album');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function viewAlbum(ApiTester $I)
    {
        $I->sendPOST('gallery/view-album', ['albumId' => 130]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType([
                'id' => 'integer',
                'title' => 'string',
                'descriptions' => 'string',
                'mediaType' => 'string',
                'mediaUrl' => 'string|array',
                'isIframe' => 'boolean|string',
                'likes' => 'integer',
                'comments' => 'integer',
                'album_id' => 'integer',
                'players' => 'array',
            ], '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }
}
