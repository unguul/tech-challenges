<?php

namespace functionnal;

use Silex\WebTestCase;

class ListSurveysTest extends WebTestCase
{

    public function createApplication()
    {
        return require ROOT_PATH . "/src/Client/Webapp/app.php";
    }

    public function testWeCanTest()
    {
        $this->assertTrue(true);
    }


}
