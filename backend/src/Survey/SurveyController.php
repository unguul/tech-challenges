<?php

namespace IWD\JOBINTERVIEW\Survey;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SurveyController
{
    public function getList(Application $app):JsonResponse{
        $surveys = [];

        $surveyFilenames = array_diff(scandir(ROOT_PATH . "/data"), [".", ".."]);
        foreach ($surveyFilenames as $surveyFilename) {
            $surveys[] = json_decode(file_get_contents(ROOT_PATH . "/data/" . $surveyFilename), true);
        }

        $surveys = array_map(
            function (array $rawSurvey) {
                return [
                    'name' => $rawSurvey['survey']['name'],
                    'code' => $rawSurvey['survey']['code'],
                ];
            },
            $surveys
        );

        $uniqueSurveys = [];

        foreach ($surveys as $survey) {
            foreach ($uniqueSurveys as $uniqueSurvey) {
                if ($uniqueSurvey['name'] === $survey['name'] && $uniqueSurvey['code'] === $survey['code']) {
                    continue 2;
                }
            }
            $uniqueSurveys[] = $survey;
        }
        usort(
            $uniqueSurveys,
            function (array $surveyA, array $surveyB) {
                if ($surveyA['name'] === $surveyB['name']) {
                    return 0;
                }
                return ($surveyA['name'] < $surveyB['name']) ? -1 : 1;
            }
        );
        return $app->json($uniqueSurveys);
    }
}