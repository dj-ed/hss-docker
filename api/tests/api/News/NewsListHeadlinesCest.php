<?php

use Codeception\Util\HttpCode;

class NewsListHeadlinesCest
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

    public $jsonType = [
        'id' => 'integer',
        'title' => 'string',
        'date' => 'integer|string:date',
        'slug' => 'string',
        'likes' => 'integer',
        'comments' => 'integer',
        'source' => 'string|NULL',
        'authorName' => 'string|NULL',
        'text' => 'string',
        'tags' => 'array',
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
        $I->sendGET('news/news-list-headlines');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function newsListHeadlines(ApiTester $I)
    {
        $I->sendPOST('news/news-list-headlines');
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
    public function newsListHeadlinesBySport(ApiTester $I)
    {
        $I->sendPOST('news/news-list-headlines', ['sportId' => $this->sportId]);
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
    public function newsListHeadlinesByGender(ApiTester $I)
    {
        $I->sendPOST('news/news-list-headlines', ['genderId' => $this->genderId]);
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
    public function newsListHeadlinesBySection(ApiTester $I)
    {
        $I->sendPOST('news/news-list-headlines', ['section' => $this->section]);
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
    public function newsListHeadlinesBySportAndFilters(ApiTester $I)
    {
        $I->sendPOST('news/news-list-headlines', [
            'sportId' => $this->sportId,
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

    /**
     * @param ApiTester $I
     */
    public function newsListHeadlinesByGenderAndFilters(ApiTester $I)
    {
        $I->sendPOST('news/news-list-headlines', [
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

    /**
     * @param ApiTester $I
     */
    public function newsListHeadlinesBySportAndGenderAndFilters(ApiTester $I)
    {
        $I->sendPOST('news/news-list-headlines', [
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
