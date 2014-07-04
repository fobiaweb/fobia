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

    protected static $_instance = array();

    /**
     * @return Fobia\Base\Application
     */
    public static function instance()
    {
        return \Fobia\Base\Application::getInstance();
    }

    /**
     * @return Fobia\DataBase\Handler\MySQL     
     */
    public static function Db()
    {
        return self::instance()->db;
    }

    /**
     * @return Fobia\Auth\Authentication 
     */
    public static function Auth()
    {
        $app = \Fobia\Base\Application::getInstance();
        return $app['auth'];
    }


}