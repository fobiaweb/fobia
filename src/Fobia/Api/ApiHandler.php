<?php
/**
 * ApiHandler class  - ApiHandler.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Api;

use Fobia\Debug\Log;

/**
 * ApiHandler class
 *
 * @package   Fobia.Api
 */
class ApiHandler
{
    /**
     * @var string директория классов
     */
    protected $classDirectory;

    /**
     * 'apiMethodName' => array('className', 'classMethod')
     * @var array
     */
    protected $apiMap = array();

    public function __construct($classDirectory = null)
    {
        $this->classDirectory = ($classDirectory) ? $classDirectory : SYSPATH . '/app/Api';
        $mapFile = $this->classDirectory . "/map.php";
        if (file_exists($mapFile)) {
            $this->apiMap = include $mapFile;
        }
    }

    /**
     * Добавляет список методов
     *
     * @param array|string $map масив методо или файл с их определениями
     */
    public function addMap($map)
    {
        if (!is_array($map)) {
            $file = $map;
            $map = \Fobia\Base\Utils::loadConfig($file);
        }
        
        $this->apiMap = array_merge($this->apiMap, (array) $map);
    }

    /**
     * Выполнить метод
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    public function request($method, $params = null)
    {
        $params = (array) $params;

        // Ищем в определениях
        if (array_key_exists($method, $this->apiMap)) {
            $map = $this->apiMap[$method];
        } else {
            Log::notice("[API]:: Method '{$method}' not in map. Start search method class.");
            $map = array('class', $this->getClass($method));
        }

        list($type, $target, $options, $targetArgs) = $map;
        if ( !is_array($options) ) {
            $options = array();
        }
        $options["name"] = $method;

        $class = null;
        $invoke = "invoke";
        try {
            switch ($type) {
                case 'file':
                    $obj = new \Fobia\Api\Method\FileMethod($target, $params, $options);
                    break;
                case 'callable':
                    $obj = \Fobia\Api\Method\CallableMethod($target, $params, $options);
                    break;
                case 'class':
                case 'object':
                    list($class, $invoke) = explode(":", $target);
                    if (!$invoke) {
                        $invoke = "invoke";
                    }
                    if (!class_exists($class)) {
                        throw new \Fobia\Api\Exception\Error("Неизвестный метод '$method'.");
                    }
                    $obj = new $class($params, $options);
                    break;
                default :
                    throw new \RuntimeException("Неверный тип '{$type}' определения метода '$method'.");
            }
            if (!method_exists($obj, $invoke)) {
                throw new \RuntimeException("Неверный [invoke] параметр '{$invoke}' метода '$method'.");
            }
        } catch (\Exception $exc) {
            return array(
                'error' => array(
                    'err_msg'  => $exc->getMessage(),
                    'err_code' => $exc->getCode(),
                    'method'   =>  $method,
                    'params'   =>  $params
                )
            );
        }

        /* @var $obj \Fobia\Api\Method\Method */
        $obj->ignoreValidationErrors();
        $obj->$invoke();

        return $obj->getFormatResponse();
    }

    /**
     * Генерирует название класса и подключает при необходимости
     *
     * @param string $method
     * @return string
     */
    public function getClass($method, $autoload = true)
    {
        $class = 'Api_' . preg_replace_callback('/^\w|_\w/', function($matches) {
            return strtoupper($matches[0]);
        }, str_replace('.', '_', $method));

        if ( ! class_exists($class) && $this->classDirectory && $autoload ) {
            Log::warning("[API]:: Class '$class' not autoloaded");
            $list = explode('.', $method);
            array_pop($list);
            array_push($list, $method);

            $file = $this->classDirectory . '/' . implode('/', $list) . '.php';
            if (file_exists($file)) {
                require_once $file;
            }

            if ( ! class_exists($class) ) {
                Log::error("[API]:: Class '$class' not exists.");
            }
        }
        // Log::debug("[API]:: Class '$class'");
        return $class;
    }
}