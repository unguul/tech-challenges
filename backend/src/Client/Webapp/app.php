<?php

declare(strict_types=1);

use IWD\JOBINTERVIEW\ResultsAggregator;
use IWD\JOBINTERVIEW\ResultsController;
use IWD\JOBINTERVIEW\Survey\Factory as SurveyFactory;
use IWD\JOBINTERVIEW\Survey\Question\Factory as QuestionFactory;
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
$app[QuestionFactory::class] = function () {
    return new QuestionFactory();
};
$app[SurveyFactory::class] = function (Application $app) {
    return new SurveyFactory($app[QuestionFactory::class]);
};
$app[SurveyRepository::class] = function (Application $app) {
    return new SurveyRepository($app[FilesystemInterface::class], $app[SurveyFactory::class]);
};
$app[ResultsAggregator::class] = function (Application $app) {
    return new ResultsAggregator();
};
$app['controller.surveys'] = function (Application $app) {
    return new SurveyController($app[SurveyRepository::class]);
};
$app['controller.results'] = function (Application $app) {
    return new ResultsController($app[SurveyRepository::class], $app[ResultsAggregator::class]);
};

$app->after(
    function (Request $request, Response $response) {
        $response->headers->set('Access-Control-Allow-Origin', '*');
    }
);
$app->get(
    '/',
    function () use ($app) {
        /** @var SurveyRepository $repository */
        $repository = $app[SurveyRepository::class];

        $surveys = $repository->findByCode("XX2");
        return $app->json(count($surveys));
    }
);
$app->get('/surveys', "controller.surveys:getList");
$app->get('/results', "controller.results:getGlobal");
$app->get('/results/{surveyCode}', "controller.results:getForSurveyCode");

$app->run();

return $app;
