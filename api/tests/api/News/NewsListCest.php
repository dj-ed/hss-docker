<?php

use Codeception\Util\HttpCode;

class NewsListCest
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
     * @var bool
     */
    public $firstLoad = true;

    /**
     * @var string
     */
    public $direction = 'next';

    /**
     * @var array
     */
    public $jsonType = [
        'id' => 'integer',
        'title' => 'string',
        'date' => 'integer|string:date',
        'source' => 'string|NULL',
        'text' => 'string',
        'tags' => 'array',
        'slug' => 'string',
        'likes' => 'integer',
        'comments' => 'integer',
        'authorName' => 'string|NULL',
        'media' => 'string|array'
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
        $I->sendGET('news/news-list');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function newsListMainSection(ApiTester $I)
    {
        $I->sendPOST('news/news-list', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'section' => $this->section,
            'firstLoad' => $this->firstLoad,
            'direction' => $this->direction
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
