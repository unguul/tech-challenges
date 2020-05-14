<?php

declare(strict_types=1);

use IWD\JOBINTERVIEW\Survey\SurveyController;
use IWD\JOBINTERVIEW\Survey\SurveyRepository;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Application();
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

//TODO: we should probably move these definitions to a separate file at least, maybe Pimple's equivalent of PHP-DI definitions file
$app[FilesystemInterface::class] = function () {
    return new Filesystem(new Local(PATH_TO_DATA));
};
$app[\IWD\JOBINTERVIEW\Survey\Factory::class] = function () {
    return new \IWD\JOBINTERVIEW\Survey\Factory();
};
$app[SurveyRepository::class] = function (Application $app) {
    return new SurveyRepository($app[FilesystemInterface::class], $app[\IWD\JOBINTERVIEW\Survey\Factory::class]);
};
$app['controller.surveys'] = function (Application $app) {
    return new SurveyController($app[SurveyRepository::class]);
};

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
$app->get('/surveys', "controller.surveys:getList");

$app->run();

return $app;
