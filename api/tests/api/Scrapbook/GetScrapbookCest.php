<?php

use Codeception\Util\HttpCode;

class GetScrapbookCest
{
    public $jsonType = [
        'media' => 'array',
        'news' => 'array',
        'album' => 'array'
    ];

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
        $I->sendGET('scrapbook/get-scrapbook');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    public function getScrapbook(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('scrapbook/get-scrapbook');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType($this->jsonType);
    }
}
