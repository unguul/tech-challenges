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

$app->run();

return $app;
