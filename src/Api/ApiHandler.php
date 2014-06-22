<?php
/**
 * ApiHandler class  - ApiHandler.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api;

/**
 * ApiHandler class
 *
 * @package   Api
 */
class ApiHandler
{

    /**
     * @var string префикс классов
     */
    protected $prefixClass;

    /**
     * @var string директория классов
     */
    protected $classDirectory;

    /**
     * 'apiMethodName' => array('className', 'classMethod')
     * @var array
     */
    protected $apimap = array();

    public function __construct()
    {
        $this->prefixClass    = 'api_';
        $this->classDirectory = __DIR__ . '/class';

        // require_once __DIR__ . '/AbstractApiInvoke.php';
        // class_alias('\\Api\\AbstractApiInvoke', 'AbstractApiInvoke');
    }

    /**
     * Выполнить метод
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    public function request($method, $params)
    {
        if (array_key_exists($method, $this->apimap)) {
            $map = $this->apimap[$method];
            $class = array_shift($map);
            $classMethod = array_shift($map);
        } else {
            $class = $this->generateApiClass($method);
            $classMethod = 'invoke';
            $map = array();
        }


        
        if ( ! class_exists($class) || ! method_exists( $class,  $classMethod)) {
            return array(
                'error' => array(
                    'err_msg'  => 'неизвестный метод',
                    'err_code' => 0,
                    'method'   =>  $method,
                    'params'   =>  $params
                )
            );
        }

        $obj = new $class($params);
        /* @var $obj \AbstractApiInvoke */

        call_user_func_array(array($obj, $classMethod), $map);
        // $obj->invoke();
        return $obj->getResponse(true);
    }

    /**
     * Генерирует название класса и подключает при необходимости
     *
     * @param string $method
     * @return string
     */
    protected function generateApiClass($method)
    {
        $class = $this->prefixClass . str_replace('.', '_', $method);
        if ( ! class_exists($class)) {
            $file = $this->classDirectory . '/' . $method . '.php';
            if (file_exists($file)) {
                require $file;
            }
        }
        return $class;
    }

}