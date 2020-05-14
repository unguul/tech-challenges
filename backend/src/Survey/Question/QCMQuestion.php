<?php

namespace IWD\JOBINTERVIEW\Survey\Question;

use IWD\JOBINTERVIEW\Survey\Question\Answer\QCMAnswer;

class QCMQuestion extends AbstractQuestion
{
    public function __construct(string $label, array $options, QCMAnswer $answer)
    {
        parent::__construct(self::TYPE_QCM, $label, $options, $answer);
    }

}