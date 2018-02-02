<?php

use Codeception\Util\HttpCode;

class ScheduleCalendarCest
{
    /**
     * @var array
     */
    public $calendarType = [
        'w' => 'week',
        'm' => 'month',
        'y' => 'year'
    ];

    /**
     * @var string
     */
    public $startDate = '2016-01-01';

    /**
     * @var string
     */
    public $endDate = '2017-12-29';

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
     * @var array
     */
    public $yearJsonType = [
        'id' => 'integer|NULL',
        'date' => 'string|string:date',
        'gameType' => 'string',
        'sportId' => 'integer',
        'count' => 'integer'
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
        $I->sendGET('game/schedule-calendar');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function scheduleCalendarWeek(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['w'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
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
    public function scheduleCalendarWeekBySport(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['w'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
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
    public function scheduleCalendarWeekByTeam(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['w'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'teamId' => $this->teamId
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
    public function scheduleCalendarWeekBySchool(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['w'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
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

    /**
     * @param ApiTester $I
     */
    public function scheduleCalendarMonth(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['m'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
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
    public function scheduleCalendarMonthBySport(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['m'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
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
    public function scheduleCalendarMonthByTeam(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['m'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'teamId' => $this->teamId
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
    public function scheduleCalendarMonthBySchool(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['m'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
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

    /**
     * @param ApiTester $I
     */
    public function scheduleCalendarYear(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['y'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->yearJsonType, '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }

    /**
     * @param ApiTester $I
     */
    public function scheduleCalendarYearBySport(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['y'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'sportId' => $this->sportId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->yearJsonType, '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }

    /**
     * @param ApiTester $I
     */
    public function scheduleCalendarYearByTeam(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['y'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'teamId' => $this->teamId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->yearJsonType, '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }

    /**
     * @param ApiTester $I
     */
    public function scheduleCalendarYearBySchool(ApiTester $I)
    {
        $I->sendPOST('game/schedule-calendar', [
            'calendarType' => $this->calendarType['y'],
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'schoolId' => $this->schoolId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data->data)) {
            $I->seeResponseMatchesJsonType($this->yearJsonType, '$.data[*]');
        } else {
            $I->seeResponseJsonMatchesJsonPath('$.data');
        }
    }
}
