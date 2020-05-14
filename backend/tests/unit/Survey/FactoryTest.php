<?php

namespace IWD\JOBINTERVIEW\Tests\unit\Survey;

use IWD\JOBINTERVIEW\Survey\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $sut;

    public function test_make()
    {
        //prepare
        $rawSurvey = json_decode(file_get_contents(PATH_TO_FIXTURES . "/surveys/0.json"), true);

        //execute
        $survey = $this->sut->make($rawSurvey);

        //assert
        $this->assertEquals("Chartres", $survey->getName());
        $this->assertEquals("XX2", $survey->getCode());
        $this->assertCount(3, $survey->getQuestions());
    }

    protected function setUp()
    {
        parent::setUp();

        $this->sut = new Factory();
    }


}
