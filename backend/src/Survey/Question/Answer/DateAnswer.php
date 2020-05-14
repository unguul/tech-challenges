<?php

namespace IWD\JOBINTERVIEW\Survey\Question\Answer;

use DateTime;

class DateAnswer implements Answer
{
    /**
     * @var DateTime
     */
    private $value;

    public function __construct(DateTime $value)
    {
        $this->value = $value;
    }

    /**
     * @return DateTime
     */
    public function getValue()
    {
        return $this->value;
    }
}