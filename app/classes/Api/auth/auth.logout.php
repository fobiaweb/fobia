<?php

/**
 * Api_Auth_logout class  - Api_Auth_logout.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
use Fobia\Api\Method\Method;

/**
 * Api_Auth_logout class
 *
 * @package
 */
class Api_Auth_Logout extends Method
{

    protected function configure()
    {
        $this->setName('auth.logout');
    }

    protected function execute()
    {
        $app = \App::instance();

        if ($app['auth']->hasIdentity()) {
            $app['auth']->logout();
            $this->response = 1;
        } else {
            $this->response = 0;
        }
    }
}