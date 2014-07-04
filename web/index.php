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

//define('REMOTE_SERVER', true);

require_once __DIR__ . '/../app/bootstrap.php';
$app = new \Fobia\Base\Application( __DIR__ . '/../app/config/config.php' );

//$logger = new \Monolog\Logger('app');
$app->map('/', function() use($app) {
    echo 'MAIN';
    $app->pass();
})->name('base');

$app->get('/info', function() use($app) {
    dump(REMOTE_SERVER);
    dump($_SERVER);
    dump($app->request->params());
    dump($_FILES);
    echo <<<HTML
<form method="post" enctype="multipart/form-data" action="/api/files.add">
<input type="file" name="file" />
<input type="submit" name="submit" />

</form>
HTML;
})->via('GET', 'POST');

//$app->route('/', '\Fobia\Base\Controller:index' )->via('GET');
$app->route('/tt', '\Fobia\Base\Controller:indexAction' )->via('GET');
$app->route('/error', '\Fobia\Base\Controller:errorAction' )->via('GET');

// Auth
$app->route('/login',  'AuthController:login')->via('GET', 'POST');
$app->route('/logout', 'AuthController:logout')->via('GET', 'POST');
$app->route('/auth',   'AuthController:auth')->via('GET');

// API
$app->route('/api/:method',   'ApiController:index')->via('ANY');


$app->route('/search(/(:section))', 'SearchController:index')->via('GET');

$app->hook('slim.after', function() use($app) {
    Log::info(\Fobia\Base\Utils::resourceUsage());
    $l = Log::getLogger();
    $logtxt = $l->render();
    $app->response->write($logtxt);

    $msg = $app->request->getClientIp()
        . ' - ' . $app['auth']->getLogin()
        . ' - ' . date('Y-m-d H:i:s')
    ;
 });

$app->run();

