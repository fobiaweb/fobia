<?php
/**
 * auth.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


require_once __DIR__ . '/../app/bootstrap.php';
$app = \App::create();


$user = new \Fobia\Auth\BaseUserIdentity();
$auth = new \Fobia\Auth\BaseAuthentication($app, $user);


var_dump($auth);
