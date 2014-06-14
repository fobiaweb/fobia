<?php
/**
 * ApiMethod class  - ApiMethod.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Api;

/**
 * ApiMethod class
 *
 * @package   Api
 *
 * @property \Fobia\Base\Application $app
 *
 */
class ApiMethod
{

    public $method;
    protected $params = array();
    public $options   = array();
    //
    protected $response;
    protected $errors = array();

    public function __construct(array $params = array())
    {
        $this->params = $params;
    }

    public function __get($name)
    {
        if ($name == 'app') {
            return \Fobia\Base\Application::getInstance();
        }
        return $this->$name;
    }

    /**
     * Выполнить подготовленый метод
     * 
     * @return boolean флаг об успехе выполнения метода
     */
    public function execute()
    {
        $args   = func_get_args();
        $params = array_merge($this->params, (array) $args[0]);

        try {
            include __DIR__ . "/methods/{$this->method}.php";
        } catch (\Exception $ex) {
            $this->errors = $ex;
            return false;
        }

        return true;
    }

    /**
     * Установка/получение параметра
     *
     * @param array|string $name
     * @param array|string $value
     *
     * @return array|string
     */
    public function params($name = null, $value = null)
    {
        $n = func_num_args();

        switch ($n) {
            case 0:
                return $this->params;
            case 1:
                if (is_array($name)) {
                    $this->params = $name;
                    return;
                }
                return $this->params[$name];
            case 2:
            default:
                $this->params[$name] = $value;
                return;
        }
    }

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return array
     */
    public function errorInfo()
    {
        return $this->errors;
    }

    protected function error($msg)
    {
        $ex         = new ApiException('error', 0);
        $ex->method = $this->method;
        $ex->params = $this->params;
        throw $ex;
    }
}