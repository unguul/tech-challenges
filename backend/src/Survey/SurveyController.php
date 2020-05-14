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
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;

class SurveyController
{

    /**
     * @var SurveyRepository
     */
    private $repository;

    /**
     * SurveyController constructor.
     * @param SurveyRepository $repository
     */
    public function __construct(SurveyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getList(Application $app): JsonResponse
    {
        $surveys = $this->repository->findAll();

        /** @var Survey[] $uniqueSurveys */
        $uniqueSurveys = array_unique($surveys);

        //sort them by name
        usort(
            $uniqueSurveys,
            function (Survey $surveyA, Survey $surveyB) {
                if ($surveyA->getName() === $surveyB->getName()) {
                    return 0;
                }
                return ($surveyA->getName() < $surveyB->getName()) ? -1 : 1;
            }
        );
        //map them surveys to just their name and code
        return $app->json(
            array_map(
                function (Survey $survey) {
                    return [
                        'name' => $survey->getName(),
                        'code' => $survey->getCode(),
                    ];
                },
                $uniqueSurveys
            )
        );
    }
}