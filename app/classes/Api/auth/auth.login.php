<?php

use Api\Method\Method;

/**
 * Авторизайия <br>
 * --------------------------------------------
 *
 * PARAMS:<br>
 * --------------------------------------------
 * <pre>
 *   login      -(*) логин
 *   password   -(*) пароль
 * </pre>
 *
 * RESULT <br>
 * --------------------------------------------<br>
 * Возвращает 0 в случии неудачи результат
 *
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 *
 * @api         auth.login
 */
class Api_Auth_Login extends Method
{

    /**
     * @var string название метода
     */
    protected $method = 'auth.login';

    protected function execute()
    {
        $p   = $this->params();
        $app = \App::instance();

        if ($app['auth']->hasIdentity()) {
            $this->response = 0;
            throw new \Api\Exception\Halt();
        }

        if ( ! $p['login'] || ! $p['password']) {
            throw new \Api\Exception\BadRequest('login', 'password');
        }

        $result = $app['auth']->login($p['login'], $p['password'], true);

        $this->response = (int) $result;
    }
}