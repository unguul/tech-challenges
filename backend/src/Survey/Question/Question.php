<?php

namespace IWD\JOBINTERVIEW\Survey\Question;

use DateTime;

interface Question
{
    const TYPE_QCM = "qcm";
    const TYPE_NUMERIC = "numeric";
    const TYPE_DATE = "date";
    const TYPES = [
        self::TYPE_QCM,
        self::TYPE_NUMERIC,
        self::TYPE_DATE,
    ];

    /**
     * @return string
     * @see Question::TYPES
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getLabel(): string;

    /**
     * @return array Returns an array of possible answers. Empty array if not applicable. i.e. when answer can be any date or number
     */
    public function getOptions(): array;

    /**
     * @return array|integer|DateTime The answer type depends on the type of question
     * @see Question::TYPES
     */
    public function getAnswer();
}