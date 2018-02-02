<?php

use Codeception\Util\HttpCode;

class CalendarCest
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
        $I->sendGET('gallery/calendar');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function calendar(ApiTester $I)
    {
        $I->sendPOST('gallery/calendar', ['seasonId' => 1,'sportId'=>1,'page'=>1]);
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
