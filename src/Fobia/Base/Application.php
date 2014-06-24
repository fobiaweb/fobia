<?php
/**
 * Application class  - Application.php file
 *
 * @author     Tyurin D. <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2012 AC Software
 */

namespace Fobia\Base;

use \Fobia\Base\Utils;
use \Fobia\Debug\Log;

/**
 * Application class
 *
 * @property \ezcDbHandler $db database
 * @property \Slim\Session $session current session
 * @property \Psr\Log\LoggerInterface $log логер
 * @property \Fobia\Auth\Authentication $auth
 *
 */
class Application extends \Slim\App
{

    const MODE_DEVELOPMENT = 'development';
    const MODE_PRODUCTION  = 'production';
    const MODE_TESTING     = 'testing';
    // protected static $instance = null;

    protected static $instance = array();

    /**
     * @return \Fobia\Base\Application
     */
    public static function getInstance($name = null)
    {
        if ($name === null) {
            $name = 0;
        }

        $app = self::$instance[$name];
        if ( ! ($app instanceof Application)) {
            throw new \RuntimeException("Error Processing Request", 1);
        }
        return $app;
    }

    /**
     * Set Instance Application
     *
     * @param \Fobia\Base\Application $app
     * @param string $name
     */
    public static function setInstance(Application $app, $name = null)
    {
        if ($name === null) {
            $name = 0;
        }
        self::$instance[$name] = $app;
    }

    /**
     * @internal
     */
    public function __construct($userSettings = null)
    {
        $configDir = '.';

        if ( ! is_array($userSettings)) {
            $file = $userSettings;
            $userSettings = array();
            if (file_exists($file)) {
                $configDir = dirname($file);

                $userSettings = Utils::loadConfig($file);
                Log::debug("Configuration load: " . realpath($file) );
                unset($file);
            }
        }


        // if (is_array($userSettings['import'])) {
        //     $import = $userSettings['import'];
        //     foreach ($import as $file) {
        //         $file     = $configDir . '/' . $file;
        //         $settings = Utils::loadConfig($file);
        //         if ($settings) {
        //             $defaultSettings = array_merge($defaultSettings, $settings);
        //             Log::debug("Configuration import",
        //                               array(realpath($file)));
        //         }
        //     }
        // }

        parent::__construct((array) $userSettings);

        // Если Internet Explorer, то шлем на хуй
        /*
          if (preg_match('/(rv:11.0|MSIE)/i', $_SERVER['HTTP_USER_AGENT'])) {
          $app->log->warning('Bad Browser ', array('HTTP_USER_AGENT'=>$_SERVER['HTTP_USER_AGENT']));
          $app->redirect( "http://fobia.github.io/docs/badbrowser.html" );
          exit();
          }
         */

        // // Автоматическая загрузка секций конфигурации
        // -------------------------------------------
        $autoload = $this['settings']['app.autoload'];
        if ($autoload) {
            $this['settings']['app']             = new \Pimple();
            $this['settings']['app']['autoload'] = $autoload;
            if (is_array($autoload)) {
                foreach (@$autoload as $cfg => $file) {
                    $this['settings']['app'][$cfg] = function($c) use($cfg, $file, $configDir) {
                        Log::debug(">> autoload config", array($cfg, $file));
                        if ( ! file_exists($configDir . "/$file")) {
                            trigger_error("Нет автозагрузочной секции конфигурации '$cfg'" . "/$file", E_USER_ERROR);
                            return;
                        }
                        return Utils::loadConfig($file);
                    };
                }
            }
        }
        unset($autoload, $cfg, $file);

        // Session
        $this->extend('session', function($session, $c) {
            Log::debug('Session start', array(session_id()));
            return $session;
        });

        // Database
        // ------------------
        $this['db'] = function($c) {
            $db = \Fobia\DataBase\DbFactory::create($c['settings']['database']);
            \ezcDbInstance::set($db);
            return $db;
        };

        $this['controller_factory'] = $this->protect(function($controller)  {
            list( $class, $action ) = explode('::', $controller);
            $app = $this;
            return function() use ( $class, $action, $app ) {
                $args = func_get_args();
                $controller = new $class( $app, $args );
                call_user_method_array($action, $controller, array() );
            };
        });

        if ( ! self::$instance[0]) {
            self::setInstance($this);
        }
    }

    /**
     * @internal
     */
    public function __get($name)
    {
        return $this[$name];
    }

    /**
     * Возвращает функцию автозоздания контролера
     *
     * @param string $controller
     * @return callable
     */
    public function createController($controller)
    {
        list( $class, $method ) = explode('::', $controller);
        if (!$method) {
            $method = 'indexAction';
        }

        $classArgs = func_get_args();
        array_shift($classArgs);
        $app = & $this;

        return function() use ($app, $classArgs, $class, $method ) {
            $methodArgs = func_get_args();
            $classRoute = new $class( $classArgs );
            return call_user_func_array(array($classRoute, $method), $methodArgs);
        };
    }

    protected function defaultNotFound()
    {
        $this->halt(404, "Not Found");
    }

    // protected function defaultError($e)
    // {
    //     $this->contentType('text/html');
    //     dump($e);
    // }

    public function isCli()
    {
        defined('IS_CLI') or define('IS_CLI',  ! defined('SYSPATH'));
        return IS_CLI;
    }

    protected function dispatchRequest(\Slim\Http\Request $request, \Slim\Http\Response $response)
    {
        try {
            $this->applyHook('slim.before');
            ob_start();
            $this->applyHook('slim.before.router');
            $dispatched = false;
            $matchedRoutes = $this['router']->getMatchedRoutes($request->getMethod(), $request->getPathInfo(), true);
            foreach ($matchedRoutes as $route) {
                try {
                    $this->applyHook('slim.before.dispatch');
                    $dispatched = $route->dispatch();
                    $this->applyHook('slim.after.dispatch');
                    if ($dispatched) {
                        break;
                    }
                } catch (\Slim\Exception\Pass $e) {
                    continue;
                }
            }
            if (!$dispatched) {
                $this->notFound();
            }
            $this->applyHook('slim.after.router');
        } catch (\Slim\Exception\Stop $e) {}
        $response->write(ob_get_clean());
        $this->applyHook('slim.after');
    }
}