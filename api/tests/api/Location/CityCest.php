<?php

use Codeception\Util\HttpCode;

class CityCest
{
    /**
     * @var int
     */
    public $seasonId = 1;

    /**
     * @var int
     */
    public $cityId = 3807;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'name' => 'string',
        'stateId' => 'integer'
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
        $I->sendGET('location/city');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function city(ApiTester $I)
    {
        $I->sendPOST('location/city', ['seasonId' => $this->seasonId, 'cityId' => $this->cityId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
