<?php

use Codeception\Util\HttpCode;

class AboutStatsCest
{
    /**
     * @var int
     */
    public $playerId = 1;

    /**
     * @var int
     */
    public $seasonId = 1;

    /**
     * @var int
     */
    public $sportId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'personalResult' => [
            'stats' => 'array',
            'total' => 'array',
            'columns' => 'array',
            'poweredBy' => 'boolean'
        ],
        'seasonStats' => [
            'stats' => 'array',
            'apg' => 'array',
            'total' => 'array',
            'allCount' => 'integer',
            'columns' => 'array',
            'poweredBy' => 'boolean'
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
        $I->sendGET('player/about-stats');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function aboutStats(ApiTester $I)
    {
        $I->sendPOST('player/about-stats', [
            'playerId' => $this->playerId,
            'seasonId' => $this->seasonId,
            'sportId' => $this->sportId
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
