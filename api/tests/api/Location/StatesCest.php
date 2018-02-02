<?php

use Codeception\Util\HttpCode;

class StatesCest
{
    /**
     * @var int
     */
    public $seasonId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'name' => 'string',
        'abbr' => 'string',
        'icon' => 'string'
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
        $I->sendGET('location/states');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function states(ApiTester $I)
    {
        $I->sendPOST('location/states', ['seasonId' => $this->seasonId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->jsonType, '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }
}
