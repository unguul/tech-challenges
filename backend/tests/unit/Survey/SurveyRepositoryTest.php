<?php

namespace IWD\JOBINTERVIEW\Tests\unit\Survey;

use IWD\JOBINTERVIEW\Survey\SurveyRepository;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
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

    public function test_findAll()
    {
        //prepare
        //execute
        $surveys = $this->sut->findAll();

        //assert
        $this->assertCount(2, $surveys);
    }

    protected function setUp()
    {
        parent::setUp();

        $this->filesystem = new Filesystem(new Local(PATH_TO_FIXTURES . "/surveys"));

        $this->sut = new SurveyRepository($this->filesystem);
    }
}
