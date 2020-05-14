<?php

namespace IWD\JOBINTERVIEW\Survey;

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

        $surveys = array_unique($surveys);

        usort($surveys, new SortByName());

        return $app->json(
            array_map(
                function (Survey $survey) {
                    return [
                        'name' => $survey->getName(),
                        'code' => $survey->getCode(),
                    ];
                },
                $surveys
            )
        );
    }
}