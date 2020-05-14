<?php

namespace IWD\JOBINTERVIEW\Tests\unit\Survey;

use IWD\JOBINTERVIEW\Survey\Factory;
use IWD\JOBINTERVIEW\Survey\Survey;
use IWD\JOBINTERVIEW\Survey\SurveyRepository;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class SurveyRepositoryTest extends TestCase
{
    /**
     * @var SurveyRepository
     */
    private $sut;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Factory|MockObject
     */
    private $factory;

    public function test_findAll()
    {
        //prepare
        $mockSurvey = $this->createMock(Survey::class);

        $this->factory->expects($this->exactly(2))->method('make')->withConsecutive(
            [$this->equalTo(json_decode(file_get_contents(PATH_TO_FIXTURES . "/surveys/0.json"), true))],
            [$this->equalTo(json_decode(file_get_contents(PATH_TO_FIXTURES . "/surveys/1.json"), true))]
        )->willReturn($mockSurvey);

        //execute
        $surveys = $this->sut->findAll();

        //assert
        $this->assertCount(2, $surveys);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->filesystem = new Filesystem(new Local(PATH_TO_FIXTURES . "/surveys"));
        $this->factory = $this->createMock(Factory::class);

        $this->sut = new SurveyRepository($this->filesystem, $this->factory);
    }
}
