<?php

use Codeception\Util\HttpCode;

class PageCest
{
    /**
     * @var string
     */
    public $url = 'about_us';

    /**
     * @var string
     */
    public $badUrl = 'about_us_page_bad';

    /**
     * @var array
     */
    public $jsonType = [
        'url' => 'string',
        'title' => 'string',
        'text' => 'string'
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
        $I->sendGET('texts/page');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function page(ApiTester $I)
    {
        $I->sendPOST('texts/page', ['url' => $this->url]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }

    /**
     * @param ApiTester $I
     */
    public function pageNotFound(ApiTester $I)
    {
        $I->sendPOST('texts/page', ['url' => $this->badUrl]);
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND); // 404
        $I->seeResponseIsJson();
    }
}
