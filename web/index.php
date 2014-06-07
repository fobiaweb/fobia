<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once __DIR__ . '/../app/bootstrap.php';
$s = microtime(true) - TIME_START;
$app = new \Fobia\Base\Application( __DIR__ . '/../app/config/config.php' );

App::instance();
Log::debug('boot: ' . $s);


$app['auth'] = function() use($app) {
    $auth = new Authentication($app);
    return $auth;
};




/*
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
        echo $app->urlFor('base', array('s'=> 'j')) . BR;
        echo "Time: " . \Fobia\Base\Utils::getExecutionTime();
        // dump($app['request']);
    })->name('base');
});

*/



$app->get('/auth', function() use($app) {
    $app['auth']->authenticate();
    dump($app['auth']->user);
    dump($app['session']['auth']['password']);
});

$app->get('/auth/login', function() use($app) {
    $app['auth']->login ('u1399934039', 2);
    dump($app['session']);
});


$app->run();

Log::alert('test');
echo '<pre>' . Log::getLogger()->readMemory() . '</pre>';