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
$app = \App::create();

if (REMOTE_SERVER) {
    $app['settings']['database.dns']     = 'mysql://srv55412_ab@localhost/srv55412_ab';
    $app['settings']['database.password']     = 'abpass';
}

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
<form method="post" enctype="multipart/form-data" action="/fobia/api/files.add">
<input type="file" name="file" />
<input type="submit" name="submit" />

</form>
HTML;
    echo "\n<hr>\n";
    $req = $app->request;
    /* @var $req \Slim\Http\Request */
    echo BR ."getHost: " . $req->getHost();
    echo BR ."getPath: " . $req->getPath();
    echo BR ."getPathInfo: " . $req->getPathInfo();
    echo BR ."getQueryString: " . $req->getQueryString();
    echo BR ."getReferer: " . $req->getReferer();
    echo BR ."getReferrer: " . $req->getReferrer();
    echo BR ."getScheme: " . $req->getScheme();
    echo BR ."getScriptName: " . $req->getScriptName();
    echo BR ."getUrl: " . $req->getUrl();
})->via('GET', 'POST');



//$app->route('/', '\Fobia\Base\Controller:index' )->via('GET');
$app->route('/error', '\Fobia\Base\Controller:errorAction' )->via('GET');

// Auth
$app->route('/login',  'AuthController:login')->via('GET', 'POST');
$app->route('/logout', 'AuthController:logout')->via('GET', 'POST');
$app->route('/auth',   'AuthController:auth')->via('GET');

// API
$app->route('/api/:method',   'ApiController:index')->via('ANY');

$app->hook('slim.after', function() use($app) {
    \Fobia\Debug\Log::info(\Fobia\Base\Utils::resourceUsage());
    $l = \Fobia\Debug\Log::getLogger();
    $logtxt = $l->render();
    $app->response->write($logtxt);

    $msg = $app->request->getClientIp()
        . ' - ' . $app['auth']->getLogin()
        . ' - ' . date('Y-m-d H:i:s')
    ;
 });

$app->run();

