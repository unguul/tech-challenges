<?php

namespace IWD\JOBINTERVIEW\Tests\unit;

use DateTime;
use IWD\JOBINTERVIEW\ResultsAggregator;
use IWD\JOBINTERVIEW\Survey\Question\Answer\DateAnswer;
use IWD\JOBINTERVIEW\Survey\Question\Answer\NumericAnswer;
use IWD\JOBINTERVIEW\Survey\Question\Answer\QCMAnswer;
use IWD\JOBINTERVIEW\Survey\Question\DateQuestion;
use IWD\JOBINTERVIEW\Survey\Question\NumericQuestion;
use IWD\JOBINTERVIEW\Survey\Question\QCMQuestion;
use IWD\JOBINTERVIEW\Survey\Survey;
use PHPUnit\Framework\TestCase;

class ResultsAggregatorTest extends TestCase
{
    /**
     * @var ResultsAggregator
     */
    private $sut;

    /**
     * @param array $surveys
     * @param int $expectedCount
     * @dataProvider repliesCountProvider
     */
    public function test_repliesCount(array $surveys, int $expectedCount)
    {
        //prepare
        $this->sut->setSurveys($surveys);

        //execute && assert
        $this->assertEquals($expectedCount, $this->sut->repliesCount());
    }

    public function repliesCountProvider(): array
    {
        return [
            [[], 0],
            [[$this->createMock(Survey::class)], 1],
            [[$this->createMock(Survey::class), $this->createMock(Survey::class)], 2],
        ];
    }

    public function test_getFirstVisitTimestamp()
    {
        //prepare
        $firstAnswer = new DateTime();
        $notFirstAnswer = new DateTime("+1 day");
        $survey1Question1 = new DateQuestion("my label", [], new DateAnswer($firstAnswer));
        $survey1Question2 = new DateQuestion("my label", [], new DateAnswer($notFirstAnswer));
        $questions = [
            $survey1Question1,
            $survey1Question2,
        ];
        $survey1 = new Survey("my survey", "whatever", $questions);
        $surveys = [
            $survey1,
        ];
        $this->sut->setSurveys($surveys);
        //execute && assert
        $this->assertEquals($firstAnswer, $this->sut->getFirstVisitTimestamp());
    }

    public function test_getLastVisitTimestamp()
    {
        //prepare
        $firstAnswer = new DateTime("-1 week");
        $lastAnswer = new DateTime("-1 day");
        $survey1Question1 = new DateQuestion("my label", [], new DateAnswer($firstAnswer));
        $survey1Question2 = new DateQuestion("my label", [], new DateAnswer($lastAnswer));
        $questions = [
            $survey1Question1,
            $survey1Question2,
        ];
        $survey1 = new Survey("my survey", "whatever", $questions);
        $surveys = [
            $survey1,
        ];
        $this->sut->setSurveys($surveys);
        //execute && assert
        $this->assertEquals($lastAnswer, $this->sut->getLastVisitTimestamp());
    }

    public function test_getMinNumberOfProducts()
    {
        //prepare
        $question1 = new NumericQuestion("my label", [], new NumericAnswer(1));
        $question2 = new NumericQuestion("my label", [], new NumericAnswer(2));
        $survey1 = new Survey("my survey", "whatever", [$question1]);
        $survey2 = new Survey("my survey", "whatever", [$question2]);
        $surveys = [
            $survey1,
            $survey2,
        ];
        $this->sut->setSurveys($surveys);

        //execute && assert
        $this->assertEquals(1, $this->sut->getMinNumberOfProducts());
    }

    public function test_getMaxNumberOfProducts()
    {
        //prepare
        $question1 = new NumericQuestion("my label", [], new NumericAnswer(1));
        $question2 = new NumericQuestion("my label", [], new NumericAnswer(2));
        $survey1 = new Survey("my survey", "whatever", [$question1]);
        $survey2 = new Survey("my survey", "whatever", [$question2]);
        $surveys = [
            $survey1,
            $survey2,
        ];
        $this->sut->setSurveys($surveys);

        //execute && assert
        $this->assertEquals(2, $this->sut->getMaxNumberOfProducts());
    }

    public function test_getAverageNumberOfProducts()
    {
        //prepare
        $question1 = new NumericQuestion("my label", [], new NumericAnswer(1));
        $question2 = new NumericQuestion("my label", [], new NumericAnswer(2));
        $question3 = new NumericQuestion("my label", [], new NumericAnswer(3));
        $survey1 = new Survey("my survey", "whatever", [$question1]);
        $survey2 = new Survey("my survey", "whatever", [$question2]);
        $survey3 = new Survey("my survey", "whatever", [$question3]);
        $surveys = [
            $survey1,
            $survey2,
            $survey3,
        ];
        $this->sut->setSurveys($surveys);

        //execute && assert
        $this->assertEquals(2, $this->sut->getAverageNumberOfProducts());
    }

    public function test_getBestSellerAvailabilityMap()
    {
        //prepare
        $options = ["Product 1", "Product 2", "Product 3", "Product 4", "Product 5", "Product 6"];
        $answer1 = [false, false, false, false, true, false];
        $answer2 = [false, false, false, false, true, false];
        $answer3 = [false, false, false, false, true, false];
        $question1 = new QCMQuestion("my label", $options, new QCMAnswer($answer1));
        $question2 = new QCMQuestion("my label", $options, new QCMAnswer($answer2));
        $question3 = new QCMQuestion("my label", $options, new QCMAnswer($answer3));
        $survey1 = new Survey("my survey", "whatever", [$question1]);
        $survey2 = new Survey("my survey", "whatever", [$question2]);
        $survey3 = new Survey("my survey", "whatever", [$question3]);
        $surveys = [
            $survey1,
            $survey2,
            $survey3,
        ];
        $this->sut->setSurveys($surveys);

        //execute && assert
        $this->assertEquals(
            [
                "Product 1" => 0,
                "Product 2" => 0,
                "Product 3" => 0,
                "Product 4" => 0,
                "Product 5" => 3,
                "Product 6" => 0,
            ],
            $this->sut->getBestSellerAvailabilityMap()
        );
    }

    protected function setUp()
    {
        parent::setUp();

        $this->sut = new ResultsAggregator();
    }
}
