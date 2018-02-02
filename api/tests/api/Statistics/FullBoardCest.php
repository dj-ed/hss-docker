<?php

use Codeception\Util\HttpCode;

class FullBoardCest
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
    public $page = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'team_id' => 'integer|NULL',
        'gp' => 'integer|NULL',
        'win' => 'string|NULL',
        'score_team' => 'string|NULL',
        'win_pct' => 'string|NULL',
        'd_count' => 'string|NULL',
        't_count' => 'string|NULL',
        'nd_count' => 'string|NULL',
        'school_id' => 'integer|NULL',
        'is_logo' => 'integer|NULL',
        'use_school_logo' => 'integer|NULL',
        'team_name' => 'string|NULL',
        'county_id' => 'integer|NULL',
        'state_id' => 'integer|NULL',
        'city' => 'string|NULL',
        'county_name' => 'string|NULL',
        'short_state_name' => 'string|NULL',
        'team_logo' => 'string|NULL'
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
        $I->sendGET('statistics/full-board');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function fullBoard(ApiTester $I)
    {
        $I->sendPOST('statistics/full-board', [
            'sportId' => $this->sportId,
            'seasonId' => $this->seasonId,
            'page' => $this->page
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
