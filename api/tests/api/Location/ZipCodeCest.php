<?php

use Codeception\Util\HttpCode;

class ZipCodeCest
{
    /**
     * @var int
     */
    public $seasonId = 1;

    /**
     * @var int
     */
    public $zipCode = 321;

    /**
     * @var array
     */
    public $jsonType = [
        'zip' => 'integer',
        'cityId' => 'integer',
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
        $I->sendGET('location/zip-code');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    public function zipCode(ApiTester $I)
    {
        $I->sendPOST('location/zip-code', ['seasonId' => $this->seasonId, 'zipCode' => $this->zipCode]);
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
