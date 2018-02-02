<?php

use Codeception\Util\HttpCode;

class InfoCest
{
    /**
     * @var int
     */
    public $schoolId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'description' => 'string|NULL',
        'contactPersons' => 'array',
        'otherPersons' => 'array'
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
        $I->sendGET('school/info');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function info(ApiTester $I)
    {
        $I->sendPOST('school/info', ['schoolId' => $this->schoolId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
