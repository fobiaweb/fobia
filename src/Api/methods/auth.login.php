<?php
/**
 * auth.login.php file
 *
 * Авторизайия
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 *  login      
 *  password   
 *
 * --------------------------------------------
 *
 * RESULT
 * ------
 * Возвращает 0 в случии неудачи результат
 * --------------------------------------------
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 *
 * @api
 */

/* @var $this   \Api\ApiMethod */
/* @var $params array */

if (! $this instanceof \Api\ApiMethod) {
    throw new \Exception('Нельзя прос так выполнить этот файл');
}


// $db = $this->app->db;

$this->app['auth']->authenticate();
if ($this->app['auth']->hasIdentity()) {
    $this->response = 0;
    return 0;
}

if (!$params['login'] || !$params['password']) {
    $this->error('Не передан один из параметров');
}

$this->response = $this->app['auth']->login($params['login'], $params['password'], true);

return;


