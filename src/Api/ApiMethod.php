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

    protected $method;
    protected $params = array();
    protected $options   = array();

    protected $response;
    protected $errors = array();

    public function __construct(array $params = array())
    {
        $this->methodsDirectory = __DIR__ . '/methods';
        $this->params = $params;
    }

    public function __get($name)
    {
        if ($name == 'app') {
            return \Fobia\Base\Application::getInstance();
        }
        return $this->$name;
    }


    public function execute()
    {
        
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
        if ($this->errors instanceof ApiException) {
            return array(
                    'err_msg' => $this->errors->getMessage(),
                    'err_code' => $this->errors->getCode()
            );
        } else {
            return $this->errors;
        }
    }

    protected function error($msg = 'error')
    {
        $ex         = new \Api\Exception_BadRequest($msg, 0);
        $ex->method = $this->method;
        $ex->params = $this->params;
        throw $ex;
    }

    protected function halt($response)
    {
        $this->response = $response;
        throw new \Api\Exception_Halt();
    }
}