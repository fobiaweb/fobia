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


$app->route('/', '\Fobia\Base\Controller:index' )->via('GET');
$app->route('/tt', '\Fobia\Base\Controller:indexAction' )->via('GET');
$app->route('/error', '\Fobia\Base\Controller:errorAction' )->via('GET');


$app->run();