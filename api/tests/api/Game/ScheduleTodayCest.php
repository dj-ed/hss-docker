<?php

use Codeception\Util\HttpCode;

class ScheduleTodayCest
{
    /**
     * @var string
     */
    public $date = '2015-11-20';

    /**
     * @var int
     */
    public $sportId = 1;

    /**
     * @var int
     */
    public $teamId = 1;

    /**
     * @var int
     */
    public $schoolId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'date' => 'string|string:date',
        'dateTime' => 'string|NULL',
        'gameType' => 'string',
        'where' => 'string',
        'win' => 'integer',
        'team' => 'string|array',
        'opponentTeam' => 'string|array',
        'scoreTeam' => 'integer|NULL',
        'scoreOpponent' => 'integer|NULL',
        'sportId' => 'integer',
        'location' => 'string'
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
        $I->sendGET('game/schedule-today');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function scheduleToday(ApiTester $I)
    {
        $I->sendPOST('game/schedule-today', [
            'date' => $this->date,
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
    public function scheduleTodayBySport(ApiTester $I)
    {
        $I->sendPOST('game/schedule-today', [
            'date' => $this->date,
            'sportId'=>$this->sportId
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
    public function scheduleTodayByTeam(ApiTester $I)
    {
        $I->sendPOST('game/schedule-today', [
            'date' => $this->date,
            'teamId'=>$this->teamId
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
    public function scheduleTodayBySchool(ApiTester $I)
    {
        $I->sendPOST('game/schedule-today', [
            'date' => $this->date,
            'schoolId'=>$this->schoolId
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
