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
class Api_Auth_Login extends ApiInvoke
{

    protected function execute()
    {
        $p = $this->params;

        $app = \App::instance();
        $app['auth']->authenticate();
        if ($app['auth']->hasIdentity()) {
            $this->halt(0);
        }

        if ( ! $p['login'] || ! $p['password']) {
            $this->error('Не передан один из параметров');
        }

        $this->response = $app['auth']->login($p['login'], $p['password'], true);

        $this->halt(1);
    }
}