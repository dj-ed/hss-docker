<?php

use Codeception\Util\HttpCode;

class GetHotTagsCest
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
        $I->sendGET('news/get-hot-tags');
        $I->seeResponseCodeIs(HttpCode::METHOD_NOT_ALLOWED); // 405
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function getHotTags(ApiTester $I)
    {
        $I->sendPOST('news/get-hot-tags');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function getHotTagsBySport(ApiTester $I)
    {
        $I->sendPOST('news/get-hot-tags', ['sportId' => $this->sportId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function getHotTagsByGender(ApiTester $I)
    {
        $I->sendPOST('news/get-hot-tags', ['genderId' => $this->genderId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function getHotTagsByIsHeadline(ApiTester $I)
    {
        $I->sendPOST('news/get-hot-tags', ['isHeadline' => $this->isHeadline]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function getHotTagsBySportAndGender(ApiTester $I)
    {
        $I->sendPOST('news/get-hot-tags', ['sportId' => $this->sportId, 'genderId' => $this->genderId]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function getHotTagsBySportAndGenderAndIsHeadline(ApiTester $I)
    {
        $I->sendPOST('news/get-hot-tags', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'isHeadline' => $this->isHeadline
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }

    /**
     * @param ApiTester $I
     */
    public function getHotTagsBySectionMain(ApiTester $I)
    {
        $I->sendPOST('news/get-hot-tags', [
            'sportId' => $this->sportId,
            'genderId' => $this->genderId,
            'isHeadline' => $this->isHeadline,
            'section'=> $this->section
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
    }
}
