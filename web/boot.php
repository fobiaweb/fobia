<?php


require_once __DIR__ . '/../app/bootstrap.php';

$app = new \Fobia\Base\Application();

$app->hook('slim.before', function($args) use($app) {
    $app->get('/ccc/:dd+', '\\Controller::index');

    $app->get('/(:page+)', function() use ($app) {
        echo "Path: ". $app->request->getPath() . BR;
        echo "PathInfo: ". $app->request->getPathInfo() . BR;
        echo "UserAgent: ". $app->request->getUserAgent() . BR;


        $path = trim($app->request->getPathInfo(), '/');
        dump( explode('/', $path) );

        echo BR;
        echo "urlFor - base (s:j): ";
        echo $app->urlFor('base', array('page'=> '11', '22')) . BR;
        echo "Time: " . \Fobia\Base\Utils::getExecutionTime();
        // dump($app['request']);
    })->name('base');
});