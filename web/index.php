<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

require_once __DIR__ . '/boot.php';

$app = App::instance();

$app->get('/', function() {
    echo 'MAIN';
    // include __DIR__ . '/../app/view/login.php';
});

$app->get('/sub', function() use($app) {
    echo $app->subRequest('/logout', 'GET')->getBody()->__toString() ;
    var_dump($app['request']->params());
    var_dump($app['request']->getBody());
});
$app->get('/file', function() use($app) {
    $file = SYSPATH . '/app/file.rar';
    $app->sendFile($file);

});
//$app->any('/test(/:h+)', $app->createController('\\Controller::indexAction'));


$app->route('/bla', 'AuthController:test')->via('GET');


$route_arr = glob(__DIR__ . '/../app/router/*.php');
foreach ($route_arr as $file) {
    include $file;
}
unset($route_arr);


$app->run();


$logger = Log::getLogger();
if (method_exists($logger, 'render')) {
    echo  Log::getLogger()->render() ;
}