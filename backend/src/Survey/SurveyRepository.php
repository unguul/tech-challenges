<?php

namespace IWD\JOBINTERVIEW\Survey;

use League\Flysystem\FilesystemInterface;

class SurveyRepository
{

    /**
     * @var FilesystemInterface
     */
    private $filesystem;
    /**
     * @var Factory
     */
    private $factory;

    public function __construct(FilesystemInterface $filesystem, Factory $factory)
    {
        $this->filesystem = $filesystem;
        $this->factory = $factory;
    }

    /**
     * @return Survey[]
     */
    public function findAll(): array
    {
        $rawSurveys = array_map(
            function (array $surveyFile) {
                return json_decode($this->filesystem->read($surveyFile['path']), true);
            },
            $this->filesystem->listContents("/")
        );
        return array_map([$this->factory, 'make'], $rawSurveys);
    }

    /**
     * @param string $code
     * @return Survey[]
     */
    public function findByCode(string $code): array
    {
        $rawSurveys = array_map(
            function (array $surveyFile) {
                return json_decode($this->filesystem->read($surveyFile['path']), true);
            },
            $this->filesystem->listContents("/")
        );

        $rawSurveys = array_filter(
            $rawSurveys,
            function (array $rawSurvey) use ($code) {
                return $rawSurvey['survey']['code'] === $code;
            }
        );

        return array_map([$this->factory, 'make'], array_values($rawSurveys));
    }
}