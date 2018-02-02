<?php

use Codeception\Util\HttpCode;

class SportLeaderboardCest
{
    /**
     * @var int
     */
    public $sportId = 1;

    /**
     * @var int
     */
    public $seasonId = 1;

    /**
     * @var int
     */
    public $genderId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'teams' => [
            [
                'gender' => 'array',
                'teamType' => 'array'
            ]
        ],
        'stats' => [
            [
                'stats' => 'array',
                'statName' => 'string|NULL'
            ]
        ]
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
        $I->sendGET('statistics/sport-leaderboard');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function sportLeaderBoard(ApiTester $I)
    {
        $I->sendPOST('statistics/sport-leaderboard', [
            'sportId' => $this->sportId,
            'seasonId' => $this->seasonId,
            'genderId' => $this->genderId,
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
