<?php
/**
 * App class  - App.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

/**
 * App class
 *
 * @package
 */
class App
{
    public static function instance()
    {
        return \Fobia\Base\Application::getInstance();
    }

    public static function Db()
    {
        return \ezcDbInstance::get();
    }
}


class Log extends \Fobia\Debug\Log {}