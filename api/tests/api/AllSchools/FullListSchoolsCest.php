<?php

use Codeception\Util\HttpCode;

class FullListSchoolsCest
{
    /**
     * @var int
     */
    public $seasonId = 1;

    /**
     * @var int
     */
    public $countyId = 384;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'name' => 'string|NULL',
        'logoUrl' => 'string|NULL',
        'state' => 'string|NULL',
        'county' => 'string|NULL',
        'sports' => 'array',
        'socials' => 'array'
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
        $I->sendGET('all-schools/full-list-schools');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function fullListSchools(ApiTester $I)
    {
        $I->sendPOST('all-schools/full-list-schools', ['seasonId' => $this->seasonId, 'countyId' => $this->countyId]);
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
