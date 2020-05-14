<?php

namespace IWD\JOBINTERVIEW\Tests\unit\Survey\Question;

use DateTime;
use IWD\JOBINTERVIEW\Survey\Question\DateQuestion;
use IWD\JOBINTERVIEW\Survey\Question\Factory;
use IWD\JOBINTERVIEW\Survey\Question\NumericQuestion;
use IWD\JOBINTERVIEW\Survey\Question\QCMQuestion;
use IWD\JOBINTERVIEW\Survey\Question\UnknownQuestionTypeException;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @var Factory
     */
    private $sut;

    /**
     * @param array $rawQuestion
     * @param string $expectedQuestionType
     * @param array $expectedOptions
     * @param $expectedAnswer
     * @dataProvider rawQuestionsProvider
     */
    public function test_make(array $rawQuestion, string $expectedQuestionType, array $expectedOptions, $expectedAnswer)
    {
        //prepared in provider
        //execute
        $question = $this->sut->make($rawQuestion);

        //assert
        $this->assertInstanceOf($expectedQuestionType, $question);
        $this->assertEquals($rawQuestion['label'], $question->getLabel());
        $this->assertEquals($expectedOptions, $question->getOptions());
        $this->assertEquals($expectedAnswer, $question->getAnswer()->getValue());
    }

    public function rawQuestionsProvider(): array
    {
        $rawSurvey = json_decode(file_get_contents(PATH_TO_FIXTURES . "/surveys/0.json"), true);

        return [
            [
                $rawSurvey['questions'][0],
                QCMQuestion::class,
                $rawSurvey['questions'][0]['options'],
                $rawSurvey['questions'][0]['answer'],
            ],
            [$rawSurvey['questions'][1], NumericQuestion::class, [], $rawSurvey['questions'][1]['answer']],
            [
                $rawSurvey['questions'][2],
                DateQuestion::class,
                [],
                DateTime::createFromFormat("Y-m-d\TH:i:s\.000\Z", $rawSurvey['questions'][2]['answer']),
            ],
        ];
    }

    public function test_make_with_unknown_type()
    {
        //prepare
        $this->expectException(UnknownQuestionTypeException::class);

        //execute
        $question = $this->sut->make(['type' => "whatever"]);
        //asserted via expectation
    }

    protected function setUp()
    {
        parent::setUp();

        $this->sut = new Factory();
    }

}
