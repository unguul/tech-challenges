<?php

namespace IWD\JOBINTERVIEW\Survey;

use League\Flysystem\FilesystemInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;

class SurveyController
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function getList(Application $app): JsonResponse
    {
        $surveys = [];

        $rawSurveyFiles = $this->filesystem->listContents("/");

        foreach ($rawSurveyFiles as $rawSurveyFile) {
            $surveys[] = json_decode($this->filesystem->read($rawSurveyFile['path']), true);
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