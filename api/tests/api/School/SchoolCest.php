<?php

use Codeception\Util\HttpCode;

class SchoolCest
{
    /**
     * @var int
     */
    public $schoolId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'name' => 'string|NULL',
        'shortName' => 'string|NULL',
        'logoUrl' => 'string|NULL',
        'stateName' => 'string|NULL',
        'city' => 'string|NULL',
        'address' => 'string|NULL',
        'zip' => 'string|NULL',
        'mainColor' => 'string|NULL',
        'secondColor' => 'string|NULL',
        'persons' => 'array',
        'phoneExt' => 'string|NULL',
        'fax' => 'string|NULL',
        'faxExt' => 'string|NULL',
        'mascot' => 'string|NULL',
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
        $I->sendGET('school');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function school(ApiTester $I)
    {
        $I->sendPOST('school', ['schoolId' => $this->schoolId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
