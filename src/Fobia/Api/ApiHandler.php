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
    protected $apimap = array();

    public function __construct($classDirectory = null)
    {
        $this->classDirectory = ($classDirectory) ? $classDirectory : SYSPATH . '/app/Api';
        $mapFile = $this->classDirectory . "/map.php";
        if (file_exists($mapFile)) {
            $this->apimap = include $mapFile;
        }
    }

    public function addMap($map)
    {
        $this->apimap = array_merge($this->apimap, (array) $map);
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
        if (array_key_exists($method, $this->apimap)) {
            $map = $this->apimap[$method];
        } else {
            Log::notice("[API]:: Method '{$method}' not in map. Start search method class.");
            $map = array('object', $this->getClass($method));
        }

        $options = @$map[2];
        if ( !is_array($options) ) {
            $options = array();
        }
        $options["name"] = $method;
        
        $class = null;
        $invoke = "invoke";
        try {
            switch ($map[0]) {
                case 'file':
                    $obj = new \Fobia\Api\Method\FileMethod($map[1], $params, $options);
                    break;
                case 'callable':
                    $obj = \Fobia\Api\Method\CallableMethod($map[1], $params, $options);
                    break;
                case 'object':
                    list($class, $invoke) = explode(":", $map[1]);
                    if (!$invoke) {
                        $invoke = "invoke";
                    }
                    $obj = new $class($params, $options);
                    break;
                default :
                    throw new \Fobia\Api\Exception\Error("none type");
            }
        } catch (\Exception $exc) {
            return array(
                'error' => array(
                    'err_msg'  => 'неизвестный метод',
                    'err_code' => 0,
                    'method'   =>  $method,
                    'params'   =>  $params,
                    'err_treace' => $exc->getMessage()
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