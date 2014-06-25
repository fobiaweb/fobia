<?php
/**
 * AuthController class  - AuthController.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Controller;

use Fobia\Base\Controller;

/**
 * AuthController class
 *
 * @package   Controller
 */
class AuthController extends Controller
{
    public function login()
    {
        $app = \App::instance();

        $app['auth']->authenticate();

        if ($app->request->isGet()) {
            if ( $app['auth']->hasIdentity() ) {
                $app->redirect($app->urlFor('base') . 'auth?r=' . rand());
            }
            include SRC_DIR . '/view/login.php';
        }

        if ($app->request->isPost()) {
            $login = $app->request->post('login');
            $password = $app->request->post('pass');
            $app->auth->login($login, $password);
            $app->redirect($app->urlFor('base') . 'login?r=' . rand());
        }
    }

    public function logout()
    {
        $app = \App::instance();

        $app['auth']->authenticate();
        $app['auth']->logout();
        $app->redirect($app->urlFor('base') . 'login?r=' . rand());
    }

    public function auth()
    {
        $app = \App::instance();
        $app['auth']->authenticate();

        if ($app['auth']->hasIdentity()) {
            $auth = $app['session']['auth'];
            $auth['page'] += 1;
            $app['session']['auth'] = $auth;
            // $app['auth']->setOnline();
            dump($app['session']);
        } else {
            echo 'NO AYTH';
        }
    }
}