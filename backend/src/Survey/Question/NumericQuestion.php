<?php

namespace IWD\JOBINTERVIEW\Survey\Question;

use IWD\JOBINTERVIEW\Survey\Question\Answer\NumericAnswer;

class NumericQuestion extends AbstractQuestion
{
    public function __construct(string $label, array $options, NumericAnswer $answer)
    {
        parent::__construct(self::TYPE_NUMERIC, $label, $options, $answer);
    }
}