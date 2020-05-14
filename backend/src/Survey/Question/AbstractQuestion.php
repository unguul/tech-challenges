<?php

namespace IWD\JOBINTERVIEW\Survey\Question;

use IWD\JOBINTERVIEW\Survey\Question\Answer\Answer;

abstract class AbstractQuestion implements Question
{
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $label;
    /**
     * @var array
     */
    protected $options;
    /**
     * @var Answer
     */
    protected $answer;

    /**
     * AbstractQuestion constructor.
     * @param string $type
     * @param string $label
     * @param array $options
     * @param Answer $answer
     */
    public function __construct(string $type, string $label, array $options, Answer $answer)
    {
        $this->type = $type;
        $this->label = $label;
        $this->options = $options;
        $this->answer = $answer;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel(string $label)
    {
        $this->label = $label;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return Answer
     */
    public function getAnswer(): Answer
    {
        return $this->answer;
    }

    /**
     * @param Answer $answer
     */
    public function setAnswer(Answer $answer)
    {
        $this->answer = $answer;
    }
}