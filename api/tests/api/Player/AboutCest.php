<?php

use Codeception\Util\HttpCode;

class AboutCest
{
    /**
     * @var int
     */
    public $playerId = 1;

    /**
     * @var string
     */
    public $sizeSystem = 'metric';

    public $jsonType = [
        'metrics' => 'array',
        'summary' => 'array',
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
        $I->sendGET('player/about');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function about(ApiTester $I)
    {
        $I->sendPOST('player/about', ['playerId' => $this->playerId]);
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
    public function aboutBySizeSystemMetric(ApiTester $I)
    {
        $I->sendPOST('player/about', ['playerId' => $this->playerId, 'sizeSystem' => $this->sizeSystem]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
