<?php

namespace IWD\JOBINTERVIEW;

use DateTime;
use IWD\JOBINTERVIEW\Survey\Question\DateQuestion;
use IWD\JOBINTERVIEW\Survey\Question\NumericQuestion;
use IWD\JOBINTERVIEW\Survey\Question\QCMQuestion;
use IWD\JOBINTERVIEW\Survey\Survey;

class ResultsAggregator
{

    /**
     * @var Survey[]
     */
    private $surveys;

    public function setSurveys(array $surveys)
    {
        $this->surveys = $surveys;
    }

    public function getFirstVisitTimestamp(): DateTime
    {
        return $this->getSortedDateQuestions()[0]->getAnswer()->getValue();
    }

    /**
     * @return DateQuestion[]
     */
    private function getSortedDateQuestions(): array
    {
        $dateQuestions = [];
        foreach ($this->surveys as $survey) {
            foreach ($survey->getQuestions() as $question) {
                if ($question instanceof DateQuestion) {
                    $dateQuestions[] = $question;
                }
            }
        }

        usort(
            $dateQuestions,
            function (DateQuestion $a, DateQuestion $b) {
                if ($a->getAnswer()->getValue() === $b->getAnswer()->getValue()) {
                    return 0;
                }
                return ($a->getAnswer()->getValue() < $b->getAnswer()->getValue()) ? -1 : 1;
            }
        );

        return $dateQuestions;
    }

    public function getLastVisitTimestamp(): DateTime
    {
        return array_reverse($this->getSortedDateQuestions())[0]->getAnswer()->getValue();
    }

    public function getMinNumberOfProducts(): int
    {
        return $this->getSortedNumericQuestions()[0]->getAnswer()->getValue();
    }

    /**
     * @return NumericQuestion[]
     */
    private function getSortedNumericQuestions(): array
    {
        $numericQuestions = [];
        foreach ($this->surveys as $survey) {
            foreach ($survey->getQuestions() as $question) {
                if ($question instanceof NumericQuestion) {
                    $numericQuestions[] = $question;
                }
            }
        }

        usort(
            $numericQuestions,
            function (NumericQuestion $a, NumericQuestion $b) {
                if ($a->getAnswer()->getValue() === $b->getAnswer()->getValue()) {
                    return 0;
                }
                return ($a->getAnswer()->getValue() < $b->getAnswer()->getValue()) ? -1 : 1;
            }
        );

        return $numericQuestions;
    }

    public function getMaxNumberOfProducts(): int
    {
        return array_reverse($this->getSortedNumericQuestions())[0]->getAnswer()->getValue();
    }

    public function getAverageNumberOfProducts(): float
    {
        $numericQuestions = $this->getSortedNumericQuestions();

        $totalNumberOfProducts = array_reduce(
            $numericQuestions,
            function (int $total, NumericQuestion $question) {
                $total += $question->getAnswer()->getValue();
                return $total;
            },
            0
        );

        return round($totalNumberOfProducts / count($numericQuestions), 2);
    }

    public function repliesCount(): int
    {
        return count($this->surveys);
    }

    public function getBestSellerAvailabilityMap(): array
    {
        $bestSellerAvailabilityMap = [];

        $questions = $this->getQCMQuestions();

        foreach ($questions as $question) {
            foreach ($question->getOptions() as $index => $option) {
                if (!isset($bestSellerAvailabilityMap[$option])) {
                    $bestSellerAvailabilityMap[$option] = 0;
                }
                if ($question->getAnswer()->getValue()[$index] === true) {
                    $bestSellerAvailabilityMap[$option]++;
                }
            }
        }
        return $bestSellerAvailabilityMap;
    }

    /**
     * @return QCMQuestion[]
     */
    private function getQCMQuestions(): array
    {
        $QCMQuestions = [];
        foreach ($this->surveys as $survey) {
            foreach ($survey->getQuestions() as $question) {
                if ($question instanceof QCMQuestion) {
                    $QCMQuestions[] = $question;
                }
            }
        }

        return $QCMQuestions;
    }
}