<?php

namespace IWD\JOBINTERVIEW\Survey\Question\Answer;

class QCMAnswer implements Answer
{
    /**
     * @var array
     */
    private $value;

    public function __construct(array $value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}