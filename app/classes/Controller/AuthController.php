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

        if ($app->request->isGet()) {
            if ( $app['auth']->hasIdentity() ) {
                $app->redirect($app->urlForBase('/auth?r=' . rand()));
            }
            include SRC_DIR . '/view/login.php';
        }

        if ($app->request->isPost()) {
            $login = $app->request->post('login');
            $password = $app->request->post('pass');
            $app->auth->login($login, $password);
            $app->redirect($app->urlForBase('/login?r=' . rand()));
        }
    }

    public function logout()
    {
        $app = \App::instance();
        $app['auth']->logout();
        $app->redirect($app->urlForBase('/login?r=' . rand()));
    }

    public function auth()
    {
        $app = \App::instance();
        if (!$app['auth']->hasIdentity()) {
            echo 'NO AYTH';
            return;
        }
        //$auth = $app['session']['auth'];
        //$auth['page'] += 1;
        //$app['session']['auth'] = $auth;


        dump(\App::Auth()->getUser());


        dump( "------------" );

        dump(\App::Auth()->isAccess('ACCESS_2'));
    }
}
