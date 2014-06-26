<?php

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
class Api_Auth_Login extends AbstractApiInvoke
{

    /**
     * @var string название метода
     */
    protected $method = 'auth.login';
    
    protected function execute()
    {
        $p = $this->params;

        $app = \App::instance();
        $app['auth']->authenticate();
        if ($app['auth']->hasIdentity()) {
            $this->halt(0);
        }

        if ( ! $p['login'] || ! $p['password']) {
            throw new \Api\Exception\BadRequest('login', 'password');
        }

        $this->response = $app['auth']->login($p['login'], $p['password'], true);

        $this->halt(1);
    }
}