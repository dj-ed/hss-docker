<?php

use Codeception\Util\HttpCode;

class FullListTeamsCest
{
    /**
     * @var int
     */
    public $seasonId = 9;

    /**
     * @var int
     */
    public $schoolId = 136;

    /**
     * @var int
     */
    public $sportId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'name' => 'string|NULL',
        'logoUrl' => 'string|NULL',
        'teamType' => 'string|NULL',
        'teamTypeLogo' => 'string|NULL',
        'genderId' => 'integer|string|NULL',
        'genderName' => 'string|NULL',
        'sportId' => 'integer',
        'leagues' => 'string|NULL',
        'social' => 'array'
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
        $I->sendGET('all-teams/full-list-teams');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function fullListTeams(ApiTester $I)
    {
        $I->sendPOST('all-teams/full-list-teams', [
            'sportId' => $this->sportId,
            'seasonId' => $this->seasonId,
            'schoolId' => $this->schoolId
        ]);
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
