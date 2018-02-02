<?php

use Codeception\Util\HttpCode;

class TeamsCest
{
    /**
     * @var int
     */
    public $seasonId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'schoolId' => 'integer|NULL',
        'schoolName' => 'string|NULL',
        'schoolShortName' => 'string|NULL',
        'schoolLogo' => 'string|NULL',
        'teamId' => 'integer|NULL',
        'teamName' => 'string|NULL',
        'sportId' => 'integer|NULL',
        'genderName' => 'string|NULL',
        'varsityName' => 'string|NULL',
        'varsityFullName' => 'string|NULL',
        'stateId' => 'integer|NULL',
        'cityId' => 'integer|NULL',
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
        $I->sendGET('location/teams');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function teams(ApiTester $I)
    {
        $I->sendPOST('location/teams', ['seasonId' => $this->seasonId]);
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
