<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TranslationTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testWithoutParams()
    {
        $this->get('/translate')
            ->seeJson(["errors" => ["There is no 'source' param","There is no 'target' param","There is no 'word' param"]]);
    }

    public function testDatabaseColumns()
    {
        $this->seeInDatabase('translations', ['id' => 1, 'ru' => 'привет', 'en' => 'hi']);
        $this->notSeeInDatabase('translations', ['id' => 1, 'ru' => 'qweqweqweqwe', 'en' => 'qweqweqweqweqwe']);
    }

    public function testLowercaseTranslate()
    {
        $this->get('/translate?word=hi&source=en&target=ru')
            ->seeJson(["data" => ["word" => "hi", "source" => "en", "target" => "ru", "result" => "привет"]]);
    }

    public function testCapitalizeTranslate()
    {
        $this->get('/translate?word=Hi&source=en&target=ru')
            ->seeJson(["data" => ["word" => "Hi", "source" => "en", "target" => "ru", "result" => "Привет"]]);
    }

    public function testUppercaseTranslate()
    {
        $this->get('/translate?word=HI&source=en&target=ru')
            ->seeJson(["data" => ["word" => "HI", "source" => "en", "target" => "ru", "result" => "ПРИВЕТ"]]);
    }

    public function testEmptyResult()
    {
        $this->get('/translate?word=HI&source=ru&target=en')
            ->seeJson(["data" => ["error" => "There is no results for request"]]);
    }

    public function testErrorResult()
    {
        $this->get('/translate?word=qweqweqwe&source=en&target=ru')
            ->seeJson(["data" => ["error" => "There is no results for request"]]);
    }

    public function testUnknownSource()
    {
        $this->get('/translate?word=hi&source=unknown_language&target=ru')
            ->seeJson(["errors" => ["There is no such 'source' language"]]);
    }

    public function testUnknownTarget()
    {
        $this->get('/translate?word=hi&source=en&target=unknown_language')
            ->seeJson(["errors" => ["There is no such 'target' language"]]);
    }
}
