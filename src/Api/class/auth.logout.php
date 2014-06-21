<?php
/**
 * Api_Auth_logout class  - Api_Auth_logout.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * Api_Auth_logout class
 *
 * @package
 */
class Api_Auth_Logout extends AbstractApiInvoke
{

    protected $method = 'auth.logout';

    protected function execute()
    {
        $app = App::instance();
        $app['auth']->logout();

        $this->response = 1;
    }
}