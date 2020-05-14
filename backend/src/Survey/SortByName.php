<?php

namespace IWD\JOBINTERVIEW\Survey;

class SortByName
{
    public function __invoke(Survey $surveyA, Survey $surveyB): int
    {
        if ($surveyA->getName() === $surveyB->getName()) {
            return 0;
        }
        return ($surveyA->getName() < $surveyB->getName()) ? -1 : 1;
    }
}