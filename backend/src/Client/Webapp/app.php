<?php

declare(strict_types=1);

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();
$app->after(
    function (Request $request, Response $response) {
        $response->headers->set('Access-Control-Allow-Origin', '*');
    }
);
$app->get(
    '/',
    function () use ($app) {
        return 'Status OK';
    }
);
$app->get(
    '/surveys',
    function () use ($app) {
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
);

$app->run();

return $app;
