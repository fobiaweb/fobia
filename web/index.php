<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/*
|---------------------------------------------------------------
| CONFIGURATION
|---------------------------------------------------------------
|
| Настройки скриптика :)
|
*/

define('REMOTE_SERVER', true);

require_once __DIR__ . '/../app/bootstrap.php';
$app = new \Fobia\Base\Application( __DIR__ . '/../app/config/config.php' );

//$logger = new \Monolog\Logger('app');
$app->get('/', function() use($app) {
    echo 'MAIN';
    $app->pass();
})->name('base');

//$app->route('/', '\Fobia\Base\Controller:index' )->via('GET');
$app->route('/tt', '\Fobia\Base\Controller:indexAction' )->via('GET');
$app->route('/error', '\Fobia\Base\Controller:errorAction' )->via('GET');

// Auth
$app->route('/login',  'AuthController:login')->via('GET', 'POST');
$app->route('/logout', 'AuthController:logout')->via('GET', 'POST');
$app->route('/auth',   'AuthController:auth')->via('GET');

// API
$app->route('/api/:method',   'ApiController:index')->via('ANY');


$app->hook('slim.after', function() use($app) {
    $l = Log::getLogger();
    $logtxt = $l->render();
    $app->response->write($logtxt);
 });

$app->run();

