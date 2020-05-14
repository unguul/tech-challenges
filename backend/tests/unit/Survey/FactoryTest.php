<?php

namespace IWD\JOBINTERVIEW\Tests\unit\Survey;

use IWD\JOBINTERVIEW\Survey\Factory;
use IWD\JOBINTERVIEW\Survey\Question\Factory as QuestionFactory;
use IWD\JOBINTERVIEW\Survey\Question\Question;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $sut;
    /**
     * @var QuestionFactory|MockObject
     */
    private $questionFactory;

    public function test_make()
    {
        //prepare
        $rawSurvey = json_decode(file_get_contents(PATH_TO_FIXTURES . "/surveys/0.json"), true);

        $this->questionFactory->expects($this->exactly(3))->method('make')->willReturn(
            $this->createMock(Question::class)
        );

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

        $this->questionFactory = $this->createMock(QuestionFactory::class);

        $this->sut = new Factory($this->questionFactory);
    }


}
