<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once __DIR__ . '/../app/bootstrap.php';

$app = new \Fobia\Base\Application();
$app = App::instance();



$app->get('/test', function() use($app) {
    dump($app);
});



$app->hook('slim.before', function($args) use($app) {
    $app->get('/ccc/:dd+', '\\Fobia\\Base\\Controller::index');

    $app->get('/(:page+)', function() use ($app) {
        echo $app->request->getPath() . BR;
        echo $app->request->getPathInfo() . BR;
        echo $app->request->getUserAgent() . BR;
        $path = $app->request->getPathInfo();


        $path = trim($path, '/');
        dump( explode('/', $path) );

        echo BR;

        echo $app->urlFor('base', array('s'=> 'j')) . BR;

        echo \Fobia\Base\Utils::getExecutionTime();
        dump($app['request']);
    })->name('base');
});


$app->run();
Log::alert('test');