<?php

namespace IWD\JOBINTERVIEW;

use IWD\JOBINTERVIEW\Survey\SurveyRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResultsController
{
    const KEY_SURVEY_NAME = "survey_name";
    const KEY_SURVEY_CODE = "survey_code";

    /**
     * @var SurveyRepository
     */
    private $repository;
    /**
     * @var ResultsAggregator
     */
    private $resultsAggregator;

    /**
     * SurveyController constructor.
     * @param SurveyRepository $repository
     * @param ResultsAggregator $resultsAggregator
     */
    public function __construct(SurveyRepository $repository, ResultsAggregator $resultsAggregator)
    {
        $this->repository = $repository;
        $this->resultsAggregator = $resultsAggregator;
    }

    public function getGlobal(Application $app): JsonResponse
    {
        $surveys = $this->repository->findAll();

        $this->resultsAggregator->setSurveys($surveys);

        return $app->json($this->resultsAggregator);
    }

    public function getForSurveyCode(Application $app, string $surveyCode): JsonResponse
    {
        $surveys = $this->repository->findByCode($surveyCode);

        if (empty($surveys)) {
            $app->abort(404, "Survey with the requested code was not found");
        }

        $this->resultsAggregator->setSurveys($surveys);

        return $app->json(
            array_merge(
                [self::KEY_SURVEY_NAME => $surveys[0]->getName(), self::KEY_SURVEY_CODE => $surveys[0]->getCode()],
                $this->resultsAggregator->jsonSerialize()
            )
        );
    }
}