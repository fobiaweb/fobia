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

    function __construct()
    {
    }

    public function request($method, $params)
    {
        $method = str_replace('.', '_', $method);
        $class = 'api_' . $method;
        if (!class_exists($class)) {
            require __DIR__ . '/class/' . $method . '.php';
        }

        $obj = new $class($params);
        /* @var $obj \ApiInvoke */
        $obj->invoke();
        return $obj->getResponse(true);
    }

}