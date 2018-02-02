<?php

use Codeception\Util\HttpCode;

class SportsCest
{
    /**
     * @var int
     */
    public $schoolId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'poweredBy' => 'boolean',
        'sports' => 'array'
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
        $I->sendGET('school/sports');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function sports(ApiTester $I)
    {
        $I->sendPOST('school/sports', ['schoolId' => $this->schoolId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
