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
App::instance();

$app['auth'] = function() use($app) {
    $auth = new Fobia\Auth\Authentication($app);
    return $auth;
};

$app->route('/', '\Fobia\Base\Controller:index' )->via('GET');
$app->route('/tt', '\Fobia\Base\Controller:indexAction' )->via('GET');
$app->route('/error', '\Fobia\Base\Controller:errorAction' )->via('GET');

$app->route('/login',  'AuthController:login')->via('GET', 'POST');
$app->route('/logout', 'AuthController:logout')->via('GET', 'POST');
$app->route('/auth',   'AuthController:auth')->via('GET');
$app->route('/api/:method',   'ApiController:index')->via('ANY');


// Log::getLogger()->registry();

$app->run();

 $l = Log::getLogger();
// CVarDumper::dump($l);


 var_dump($l);
 