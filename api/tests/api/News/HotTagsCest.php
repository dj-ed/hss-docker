<?php

use Codeception\Util\HttpCode;

class HotTagsCest
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
    public $isHeadline = 1;

    /**
     * @var string
     */
    public $section = 'main';

    /**
     * @var array
     */
    public $tagName = [
        'all',
        'providence',
        'atlantic'
    ];

    /**
     * @var array
     */
    public $tagType = [
        'news',
        'video',
        'popular',
        'all'
    ];

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
        $I->sendGET('news/hot-tags');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function hotTagsAll(ApiTester $I)
    {
        $I->sendPOST('news/hot-tags', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'isHeadline' => $this->isHeadline,
            'section' => $this->section,
            'firstLoad' => $this->firstLoad,
            'direction' => $this->direction,
            'tagName' => $this->tagName[0],
            'tagType' => $this->tagType[3]
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
    public function hotTagsNews(ApiTester $I)
    {
        $I->sendPOST('news/hot-tags', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'isHeadline' => $this->isHeadline,
            'section' => $this->section,
            'firstLoad' => $this->firstLoad,
            'direction' => $this->direction,
            'tagName' => $this->tagName[1],
            'tagType' => $this->tagType[0]
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
    public function hotTagsVideo(ApiTester $I)
    {
        $I->sendPOST('news/hot-tags', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'isHeadline' => $this->isHeadline,
            'section' => $this->section,
            'firstLoad' => $this->firstLoad,
            'direction' => $this->direction,
            'tagName' => $this->tagName[1],
            'tagType' => $this->tagType[1]
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
    public function hotTagsPopular(ApiTester $I)
    {
        $I->sendPOST('news/hot-tags', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'isHeadline' => $this->isHeadline,
            'section' => $this->section,
            'firstLoad' => $this->firstLoad,
            'direction' => $this->direction,
            'tagName' => $this->tagName[2],
            'tagType' => $this->tagType[2]
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
