<?php

namespace IWD\JOBINTERVIEW\Tests\unit\Survey;

use IWD\JOBINTERVIEW\Survey\SortByName;
use IWD\JOBINTERVIEW\Survey\Survey;
use PHPUnit\Framework\TestCase;

class SortByNameTest extends TestCase
{
    /**
     * @var SortByName
     */
    private $sut;

    /**
     * @param Survey $a
     * @param Survey $b
     * @param int $expectedOutput
     * @dataProvider surveysProvider
     */
    public function test(Survey $a, Survey $b, int $expectedOutput)
    {
        //prepared in provider
        //execute && assert
        $this->assertEquals($expectedOutput, $this->sut->__invoke($a, $b));
    }

    public function surveysProvider(): array
    {
        $a = new Survey("a", "", []);
        $b = new Survey("b", "", []);
        return [
            [$a, $a, 0],
            [$a, $b, -1],
            [$b, $a, 1],
        ];
    }

    protected function setUp()
    {
        parent::setUp();

        $this->sut = new SortByName();
    }


}
