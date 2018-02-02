<?php

use Codeception\Util\HttpCode;

class LatestNewsCest
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
     * @var string
     */
    public $section = 'main';

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'title' => 'string',
        'date' => 'integer|string:date',
        'slug' => 'string',
        'likes' => 'integer',
        'comments' => 'integer',
        'thumbUrl' => 'string',
        'sport' => 'string',
        'gender' => 'string',
        'source' => 'string|NULL',
        'authorName' => 'string|NULL',
        'description' => 'string'
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
        $I->sendGET('news/latest-news');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function latestNewsMainSection(ApiTester $I)
    {
        $I->sendPOST('news/latest-news', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'section' => $this->section
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
