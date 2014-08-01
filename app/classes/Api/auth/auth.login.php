<?php

use Fobia\Api\Method\Method;

/**
 * Авторизайия 
 * --------------------------------------------
 *
 * PARAMS:
 * -------
 * 
 *   login      - (*) логин
 *   password   - (*) пароль
 *
 * --------------------------------------------
 *
 * RESULT:
 * -------
 * Возвращает 0 в случии неудачи результат
 * --------------------------------------------
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 *
 * @api         auth.login
 */
class Api_Auth_Login extends Method
{
    protected function configure()
    {
        $this->setName('auth.login');

        $this->setDefinition(array(
            'name' => 'login',
            'mode' => Method::VALUE_REQUIRED
        ));
        $this->setDefinition(array(
            'name' => 'password',
            'mode' => Method::VALUE_REQUIRED
        ));
    }


    protected function execute()
    {
        $p   = $this->getDefinitionParams();
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