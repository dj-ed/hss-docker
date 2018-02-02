<?php

use Codeception\Util\HttpCode;

class AlbumsCest
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
        $I->sendGET('gallery/albums');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function teamAlbums(ApiTester $I)
    {
        $I->sendPOST('gallery/albums', ['teamId' => 1]);
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
                'date' => 'integer|string:date',
                'isIframe' => 'boolean|string',
                'mediaUrl' => 'string|array',
                'likesCommentsCount' => 'string|array',
                'gameId' => 'integer|string',
                'gameData' => 'array',
                'countPhoto' => 'integer',
                'countVideo' => 'integer'
            ], '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }

    /**
     * @param ApiTester $I
     */
    public function playerAlbums(ApiTester $I)
    {
        $I->sendPOST('gallery/albums', ['playerId' => 1]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType([
                'id' => 'integer',
                'title' => 'string',
                'descriptions' => 'string|NULL',
                'mediaType' => 'string',
                'date' => 'integer|string:date',
                'isIframe' => 'boolean|string',
                'mediaUrl' => 'string|array',
                'likesCommentsCount' => 'string|array',
                'gameId' => 'integer|string',
                'gameData' => 'array',
                'countPhoto' => 'integer',
                'countVideo' => 'integer'
            ], '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }
}
