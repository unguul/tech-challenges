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
        $rawSurveys = [];

        //grab json from fs
        $surveyFiles = $this->filesystem->listContents("/");
        //parse json
        foreach ($surveyFiles as $surveyFile) {
            $rawSurveys[] = json_decode($this->filesystem->read($surveyFile['path']), true);
        }

        return array_map([$this->factory, 'make'], $rawSurveys);
    }
}