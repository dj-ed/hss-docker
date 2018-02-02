<?php

use Codeception\Util\HttpCode;

class LeaderboardCest
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
    public $seasonId = 1;

    /**
     * @var int
     */
    public $varsityId = 1;

    /**
     * @var int
     */
    public $teamId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'stats' => [
            [

                'stats' => 'array',
                'statName' => 'string'
            ]
        ],
        'innerColumns' => 'array',
        'innerColumnsName' => 'array'
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
        $I->sendGET('statistics/leaderboard');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function leaderBoard(ApiTester $I)
    {
        $I->sendPOST('statistics/leaderboard', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'seasonId' => $this->seasonId,
            'varsityId' => $this->varsityId,
            'teamId' => $this->teamId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }
}
