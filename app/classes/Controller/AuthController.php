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
    public function test()
    {
        dump(\App::instance());
    }
}