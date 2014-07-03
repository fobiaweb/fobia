<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


define('REMOTE_SERVER', true);

require_once __DIR__ . '/../../app/bootstrap.php';
$app = new \Fobia\Base\Application( __DIR__ . '/../../app/config/config.php' );

//$logger = new \Monolog\Logger('app');
$app->get('/', function() use($app) {
    echo 'MAIN';
})->name('base');

$app->hook('slim.after', function() use($app) {
    $l = Log::getLogger();
    $logtxt = $l->render();
    $app->response->write($logtxt);
 });

$app->run();
