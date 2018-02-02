<?php

use Codeception\Util\HttpCode;

class StandingsCest
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
    public $teamId = 1;

    /**
     * @var array
     */
    public $jsonType = [
        'columns' => 'array',
        'columnsName' => 'array',
        'teams' => [
            [
                'team_id' => 'integer|NULL',
                'score_team' => 'string|NULL',
                'is_logo' => 'integer|NULL',
                'use_school_logo' => 'integer|NULL',
                'team_name' => 'string|NULL',
                'sport_id' => 'integer|NULL',
                'sport_title' => 'string|NULL',
                'county_id' => 'integer|NULL',
                'state_id' => 'integer|NULL',
                'county_name' => 'string|NULL',
                'district' => 'string|NULL',
                'team_logo' => 'string|NULL',
                'teamTotalStats' => 'array',
                'topPlayersStats' => 'array'
            ]
        ]
    ];

    /**
     * @var array
     */
    public $jsonTypeByTeam = [
        'columns' => 'array',
        'columnsName' => 'array',
        'teams' => [
            [
                'team_id' => 'integer|NULL',
                'score_team' => 'string|NULL',
                'is_logo' => 'integer|NULL',
                'use_school_logo' => 'integer|NULL',
                'team_name' => 'string|NULL',
                'sport_id' => 'integer|NULL',
                'sport_title' => 'string|NULL',
                'county_id' => 'integer|NULL',
                'state_id' => 'integer|NULL',
                'county_name' => 'string|NULL',
                'district' => 'string|NULL',
                'team_logo' => 'string|NULL',
                'teamTotalStats' => 'array',
                'team_photo' => 'string|NULL'
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
        $I->sendGET('statistics/standings');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    public function standings(ApiTester $I)
    {
        $I->sendPOST('statistics/standings', [
            'sportId' => $this->sportId,
            'seasonId' => $this->seasonId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonType);
        }
    }

    public function standingsByTeamId(ApiTester $I)
    {
        $I->sendPOST('statistics/standings', [
            'sportId' => $this->sportId,
            'seasonId' => $this->seasonId,
            'teamId' => $this->teamId
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $grab = $I->grabResponse();
        $data = json_decode($grab);
        if (!empty($data)) {
            $I->seeResponseMatchesJsonType($this->jsonTypeByTeam);
        }
    }
}
