<?php

namespace IWD\JOBINTERVIEW\Survey;

use IWD\JOBINTERVIEW\Survey\Question\Factory as QuestionFactory;

/**
 * Class Factory
 * @package IWD\JOBINTERVIEW\Survey
 */
class Factory
{
    /**
     * @var QuestionFactory
     */
    private $questionFactory;

    /**
     * Factory constructor.
     * @param QuestionFactory $questionFactory
     */
    public function __construct(QuestionFactory $questionFactory)
    {
        $this->questionFactory = $questionFactory;
    }

    /**
     * @param array $rawSurvey
     * @return Survey
     */
    public function make(array $rawSurvey): Survey
    {
        $name = $rawSurvey['survey']['name'];
        $code = $rawSurvey['survey']['code'];
        $questions = array_map([$this->questionFactory, 'make'], $rawSurvey['questions']);

        return new Survey($name, $code, $questions);
    }
}