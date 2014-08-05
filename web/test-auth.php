<?php
/**
 * auth.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once __DIR__ . '/../app/bootstrap.php';
$app = \App::create();

$app->session['var'] = $app->session['var'] + 1;

//$user = new \Fobia\Auth\BaseUserIdentity();
$auth = new \Fobia\Auth\BaseAuthentication($app);
$auth->authenticate();
if (isset($_GET['login'])) {
    $login = $_GET['login'];
    $password = $_GET['password'];

    $r = $auth->login($login, $password);
}
//$app->session->clear();
var_dump($auth->getUser());
var_dump($app->session);

echo \Fobia\Debug\Log::getLogger()->render();
$app->get('/', function(){});
$app->run();
