<?php

namespace IWD\JOBINTERVIEW\Survey\Question;

use IWD\JOBINTERVIEW\Survey\Question\Answer\DateAnswer;

class DateQuestion extends AbstractQuestion
{
    public function __construct(string $label, array $options, DateAnswer $answer)
    {
        parent::__construct(self::TYPE_DATE, $label, $options, $answer);
    }
}