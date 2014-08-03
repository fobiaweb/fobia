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
    /**
     * @return Fobia\Base\Application
     */
    public static function create($config = null)
    {
        if ($config === null) {
            $config = array(
                'file' => array(
                    CONFIG_DIR . '/config.php',
                    CONFIG_DIR . '/config.local.php'
                )
            );
        }
        $app = new \Fobia\Base\Application($config);
        return $app;
    }

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
        $app = self::instance();
        return $app['auth'];
    }
}