<?php

use Codeception\Util\HttpCode;

class UpcomingCest
{
    /**
     * @var int
     */
    public $sportId = 1;

    /**
     * @var int
     */
    public $genderId = 1;

    /**
     * @var int
     */
    public $teamId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'date' => 'string|string:date',
        'dateTime' => 'string|NULL',
        'gameType' => 'string',
        'where' => 'string',
        'team' => 'string|array',
        'opponentTeam' => 'string|array',
        'sportId' => 'integer',
    ];

    /**
     * @var int
     */
    public $schoolId = 1;

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
        $I->sendGET('game/upcoming');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function upcoming(ApiTester $I)
    {
        $I->sendPOST('game/upcoming', [
            'sportId' => $this->sportId
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

    /**
     * @param ApiTester $I
     */
    public function upcomingByGender(ApiTester $I)
    {
        $I->sendPOST('game/upcoming', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
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

    /**
     * @param ApiTester $I
     */
    public function upcomingByTeam(ApiTester $I)
    {
        $I->sendPOST('game/upcoming', [
            'sportId' => $this->sportId,
            'teamId' => $this->teamId,
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

    /**
     * @param ApiTester $I
     */
    public function upcomingBySchool(ApiTester $I)
    {
        $I->sendPOST('game/upcoming', [
            'sportId' => $this->sportId,
            'schoolId' => $this->schoolId,
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
