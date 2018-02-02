<?php

use Codeception\Util\HttpCode;

class AddEventCest
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
        $I->sendGET('events/add-event');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function badModelType(ApiTester $I)
    {
        $I->sendPOST('events/add-event', ['modelId' => 1, 'modelType' => 'badModelType']);
        $I->seeResponseCodeIs(HttpCode::INTERNAL_SERVER_ERROR); // 500
    }

    /**
     * @param ApiTester $I
     */
    public function addEvent(ApiTester $I)
    {
        $I->amUser();
        $I->sendPOST('events/add-event', ['modelId' => 14, 'modelType' => 'game']);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.success');
        $I->seeResponseMatchesJsonType(['success' => 'boolean|string']);
    }
}
