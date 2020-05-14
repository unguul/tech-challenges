<?php

namespace IWD\JOBINTERVIEW\Survey\Question;

use DateTime;
use IWD\JOBINTERVIEW\Survey\Question\Answer\DateAnswer;
use IWD\JOBINTERVIEW\Survey\Question\Answer\NumericAnswer;
use IWD\JOBINTERVIEW\Survey\Question\Answer\QCMAnswer;

/**
 * Class Factory
 * @package IWD\JOBINTERVIEW\Survey\Question
 */
class Factory
{
    const KEY_TYPE = 'type';
    const KEY_LABEL = 'label';
    const KEY_OPTIONS = 'options';
    const KEY_ANSWER = 'answer';
    const FORMAT_DATE_ANSWER = "Y-m-d\TH:i:s\.000\Z";

    /**
     * @param array $rawQuestion
     * @return Question
     */
    public function make(array $rawQuestion): Question
    {
        switch ($rawQuestion[self::KEY_TYPE]) {
            case Question::TYPE_QCM:
                return $this->makeQCMQuestion($rawQuestion);
            case Question::TYPE_NUMERIC:
                return $this->makeNumericQuestion($rawQuestion);
            case Question::TYPE_DATE:
                return $this->makeDateQuestion($rawQuestion);
        }
        throw new UnknownQuestionTypeException();
    }

    /**
     * @param array $rawQuestion
     * @return QCMQuestion
     */
    private function makeQCMQuestion(array $rawQuestion): QCMQuestion
    {
        $label = $rawQuestion[self::KEY_LABEL];
        $options = self::parseOptions($rawQuestion[self::KEY_OPTIONS]);
        $answer = new QCMAnswer($rawQuestion[self::KEY_ANSWER]);
        return new QCMQuestion($label, $options, $answer);
    }

    /**
     * @param array|null $rawOptions
     * @return array
     */
    private static function parseOptions($rawOptions): array
    {
        if (is_array($rawOptions)) {
            return $rawOptions;
        }
        return [];
    }

    /**
     * @param array $rawQuestion
     * @return NumericQuestion
     */
    private function makeNumericQuestion(array $rawQuestion): NumericQuestion
    {
        $label = $rawQuestion[self::KEY_LABEL];
        $options = self::parseOptions($rawQuestion[self::KEY_OPTIONS]);
        $answer = new NumericAnswer($rawQuestion[self::KEY_ANSWER]);
        return new NumericQuestion($label, $options, $answer);
    }

    /**
     * @param array $rawQuestion
     * @return DateQuestion
     */
    private function makeDateQuestion(array $rawQuestion): DateQuestion
    {
        $label = $rawQuestion[self::KEY_LABEL];
        $options = self::parseOptions($rawQuestion[self::KEY_OPTIONS]);
        $answer = new DateAnswer(self::parseDateAnswer($rawQuestion[self::KEY_ANSWER]));
        return new DateQuestion($label, $options, $answer);
    }

    /**
     * //TODO: I'm not 100% sure of the format here. I used a hard coded string for the `.000Z` part. Maybe I'm missing something.
     * @param string $rawDateAnswer
     * @return DateTime
     */
    private function parseDateAnswer(string $rawDateAnswer): DateTime
    {
        return DateTime::createFromFormat(self::FORMAT_DATE_ANSWER, $rawDateAnswer);
    }
}