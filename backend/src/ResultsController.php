<?php

namespace IWD\JOBINTERVIEW;

use IWD\JOBINTERVIEW\Survey\SurveyRepository;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResultsController
{
    const KEY_TOTAL_REPLIES = "total_replies";
    const KEY_FIRST_STORE_VISIT = "first_store_visit";
    const KEY_LAST_STORE_VISIT = "last_store_visit";
    const KEY_MIN_NUMBER_OF_PRODUCTS = "min_number_of_products";
    const KEY_MAX_NUMBER_OF_PRODUCTS = "max_number_of_products";
    const KEY_AVERAGE_NUMBER_OF_PRODUCTS = "average_number_of_products";
    const KEY_BEST_SELLER_AVAILABILITY_MAP = "best_seller_availability";
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

        return $app->json(
            [
                self::KEY_TOTAL_REPLIES => $this->resultsAggregator->repliesCount(),
                self::KEY_FIRST_STORE_VISIT => $this->resultsAggregator->getFirstVisitTimestamp()->format(DATE_W3C),
                self::KEY_LAST_STORE_VISIT => $this->resultsAggregator->getLastVisitTimestamp()->format(DATE_W3C),
                self::KEY_MIN_NUMBER_OF_PRODUCTS => $this->resultsAggregator->getMinNumberOfProducts(),
                self::KEY_MAX_NUMBER_OF_PRODUCTS => $this->resultsAggregator->getMaxNumberOfProducts(),
                self::KEY_AVERAGE_NUMBER_OF_PRODUCTS => $this->resultsAggregator->getAverageNumberOfProducts(),
                self::KEY_BEST_SELLER_AVAILABILITY_MAP => $this->resultsAggregator->getBestSellerAvailabilityMap(),
            ]
        );
    }

    public function getForSurveyCode(Application $app, string $surveyCode): JsonResponse
    {
        $surveys = $this->repository->findByCode($surveyCode);

        if (empty($surveys)) {
            $app->abort(404, "Survey with the requested code was not found");
        }

        $this->resultsAggregator->setSurveys($surveys);

        return $app->json(
            [
                self::KEY_SURVEY_NAME => $surveys[0]->getName(),
                self::KEY_SURVEY_CODE => $surveys[0]->getCode(),
                self::KEY_TOTAL_REPLIES => $this->resultsAggregator->repliesCount(),
                self::KEY_FIRST_STORE_VISIT => $this->resultsAggregator->getFirstVisitTimestamp()->format(DATE_W3C),
                self::KEY_LAST_STORE_VISIT => $this->resultsAggregator->getLastVisitTimestamp()->format(DATE_W3C),
                self::KEY_MIN_NUMBER_OF_PRODUCTS => $this->resultsAggregator->getMinNumberOfProducts(),
                self::KEY_MAX_NUMBER_OF_PRODUCTS => $this->resultsAggregator->getMaxNumberOfProducts(),
                self::KEY_AVERAGE_NUMBER_OF_PRODUCTS => $this->resultsAggregator->getAverageNumberOfProducts(),
                self::KEY_BEST_SELLER_AVAILABILITY_MAP => $this->resultsAggregator->getBestSellerAvailabilityMap(),
            ]
        );
    }
}