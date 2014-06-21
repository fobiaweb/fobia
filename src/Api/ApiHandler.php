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

    public function __construct()
    {
        $this->prefixClass    = 'api_';
        $this->classDirectory = __DIR__ . '/class';
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
        $class = $this->generateApiClass($method);
        if ( ! class_exists($class)) {
            //
        } else {
            $obj = new $class($params);
            /* @var $obj \AbstractApiInvoke */
            $obj->invoke();
            return $obj->getResponse(true);
        }
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