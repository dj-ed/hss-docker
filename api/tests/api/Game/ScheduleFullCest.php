<?php

use Codeception\Util\HttpCode;

class ScheduleFullCest
{
    /**
     * @var integer
     */
    public $limit = 0;

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
        $I->sendGET('game/schedule-full');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function scheduleFull(ApiTester $I)
    {
        $I->sendPOST('game/schedule-full', [
            'allGames' => $this->limit
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.data');
    }

    /**
     * @param ApiTester $I
     */
    public function scheduleFullBySport(ApiTester $I)
    {
        $I->sendPOST('game/schedule-full', [
            'allGames' => $this->limit,
            'sportId' => $this->sportId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.data');
    }

    /**
     * @param ApiTester $I
     */
    public function scheduleFullByTeam(ApiTester $I)
    {
        $I->sendPOST('game/schedule-full', [
            'allGames' => $this->limit,
            'teamId' => $this->teamId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.data');
    }

    /**
     * @param ApiTester $I
     */
    public function scheduleFullBySchool(ApiTester $I)
    {
        $I->sendPOST('game/schedule-full', [
            'allGames' => $this->limit,
            'schoolId' => $this->schoolId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$.data');
    }
}
