<?php

namespace functionnal;

use Silex\WebTestCase;

class ListSurveysTest extends WebTestCase
{

    public function createApplication()
    {
        return require ROOT_PATH . "/src/Client/Webapp/app.php";
    }

    public function test_we_can_list_surveys()
    {
        //prepare
        $client = $this->createClient();

        //execute
        $crawler = $client->request('GET', '/surveys');

        //assert
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(
            json_decode(file_get_contents(PATH_TO_FIXTURES . "/surveys.json"), true),
            json_decode($client->getResponse()->getContent(), true)
        );
    }
}
