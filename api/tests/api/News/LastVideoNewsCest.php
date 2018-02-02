<?php

use Codeception\Util\HttpCode;

class LastVideoNewsCest
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
        'videoUrl' => 'string|NULL',
        'videoType' => 'string|NULL',
        'source' => 'string|NULL',
        'authorName' => 'string|NULL',
        'description' => 'string|NULL'
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
        $I->sendGET('news/last-video-news');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    public function lastVideoNewsMainSection(ApiTester $I)
    {
        $I->sendPOST('news/last-video-news', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'section' => $this->section
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
