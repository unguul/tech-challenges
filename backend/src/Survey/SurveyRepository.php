<?php

namespace IWD\JOBINTERVIEW\Survey;

use DateTime;
use IWD\JOBINTERVIEW\Survey\Question\Answer\DateAnswer;
use IWD\JOBINTERVIEW\Survey\Question\Answer\NumericAnswer;
use IWD\JOBINTERVIEW\Survey\Question\Answer\QCMAnswer;
use IWD\JOBINTERVIEW\Survey\Question\DateQuestion;
use IWD\JOBINTERVIEW\Survey\Question\NumericQuestion;
use IWD\JOBINTERVIEW\Survey\Question\QCMQuestion;
use IWD\JOBINTERVIEW\Survey\Question\Question;
use League\Flysystem\FilesystemInterface;

class SurveyRepository
{

    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @return Survey[]
     */
    public function findAll(): array
    {
        $surveys = [];

        //grab json from fs
        $surveyFiles = $this->filesystem->listContents("/");
        //parse json
        foreach ($surveyFiles as $surveyFile) {
            $surveys[] = json_decode($this->filesystem->read($surveyFile['path']), true);
        }

        //parse json into business objects
        /** @var Survey[] $surveys */
        $surveys = array_map(
            function (array $rawSurvey) {
                $name = $rawSurvey['survey']['name'];
                $code = $rawSurvey['survey']['code'];

                $questions = [];

                foreach ($rawSurvey['questions'] as $rawQuestion) {
                    switch ($rawQuestion['type']) {
                        case Question::TYPE_QCM:
                            $label = $rawQuestion['label'];
                            $options = $rawQuestion['options'];
                            $answer = new QCMAnswer($rawQuestion['answer']);
                            $questions[] = new QCMQuestion($label, $options, $answer);
                            break;
                        case Question::TYPE_NUMERIC:
                            $label = $rawQuestion['label'];
                            $options = $rawQuestion['options'] === null ? [] : [$rawQuestion['options']];
                            $answer = new NumericAnswer($rawQuestion['answer']);
                            $questions[] = new NumericQuestion($label, $options, $answer);
                            break;
                        case Question::TYPE_DATE:
                            $label = $rawQuestion['label'];
                            $options = $rawQuestion['options'] === null ? [] : [$rawQuestion['options']];
                            //TODO: I'm not sure what timestamp format this json is in, I'm using the hard-coded variant so I can move on. I've not usually included timezones in my code as everything was GMT
                            $answer = new DateAnswer(
                                DateTime::createFromFormat("Y-m-d\TH:i:s\.000\Z", $rawQuestion['answer'])
                            );
                            $questions[] = new DateQuestion($label, $options, $answer);
                            break;
                    }
                }

                return new Survey($name, $code, $questions);
            },
            $surveys
        );

        return $surveys;
    }
}