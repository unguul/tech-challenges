<?php

namespace IWD\JOBINTERVIEW;

use DateTime;
use IWD\JOBINTERVIEW\Survey\Question\DateQuestion;
use IWD\JOBINTERVIEW\Survey\Question\NumericQuestion;
use IWD\JOBINTERVIEW\Survey\Question\QCMQuestion;
use IWD\JOBINTERVIEW\Survey\Survey;

class ResultsAggregator implements \JsonSerializable
{
    const KEY_TOTAL_REPLIES = "total_replies";
    const KEY_FIRST_STORE_VISIT = "first_store_visit";
    const KEY_LAST_STORE_VISIT = "last_store_visit";
    const KEY_MIN_NUMBER_OF_PRODUCTS = "min_number_of_products";
    const KEY_MAX_NUMBER_OF_PRODUCTS = "max_number_of_products";
    const KEY_AVERAGE_NUMBER_OF_PRODUCTS = "average_number_of_products";
    const KEY_BEST_SELLER_AVAILABILITY_MAP = "best_seller_availability";
    const KEY_BEST_SELLER_AVAILABILITY_RANKING = "best_seller_availability_ratio";

    /**
     * @var Survey[]
     */
    private $surveys;

    public function setSurveys(array $surveys)
    {
        $this->surveys = $surveys;
    }

    public function jsonSerialize()
    {
        return [
            self::KEY_TOTAL_REPLIES => $this->repliesCount(),
            self::KEY_FIRST_STORE_VISIT => $this->getFirstVisitTimestamp()->format(DATE_W3C),
            self::KEY_LAST_STORE_VISIT => $this->getLastVisitTimestamp()->format(DATE_W3C),
            self::KEY_MIN_NUMBER_OF_PRODUCTS => $this->getMinNumberOfProducts(),
            self::KEY_MAX_NUMBER_OF_PRODUCTS => $this->getMaxNumberOfProducts(),
            self::KEY_AVERAGE_NUMBER_OF_PRODUCTS => $this->getAverageNumberOfProducts(),
            self::KEY_BEST_SELLER_AVAILABILITY_MAP => $this->getBestSellerAvailabilityMap(),
            self::KEY_BEST_SELLER_AVAILABILITY_RANKING => $this->getBestSellerAvailabilityRatioMap(),
        ];
    }

    public function repliesCount(): int
    {
        return count($this->surveys);
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

    public function getBestSellerAvailabilityRatioMap(): array
    {
        $maxAvailability = $this->repliesCount();
        $bestSellerAvailabilityMap = $this->getBestSellerAvailabilityMap();

        $bestSellerAvailabilityRatioMap = [];

        foreach ($bestSellerAvailabilityMap as $label => $availability) {
            $bestSellerAvailabilityRatioMap[$label] = number_format(($availability / $maxAvailability) * 100, 2) . " %";
        }
        return $bestSellerAvailabilityRatioMap;
    }
}