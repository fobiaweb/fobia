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
class ApiMethodCommand extends ApiMethod
{
    /**
     * 
     * @api     package.method
     */ 
    public function methodName()
    {
        $p = $this->params;
        
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
            $file = $this->methodsDirectory . '/' . $this->method . '.php';
            if (!file_exists($file)) {
                $this->error('Не известный метом');
            }
            $r = include $file ;
            if ($r !== null) {
                $this->response = $r;
            }
        } catch (\Api\ApiException $ex) {
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
        $ex         = new ApiException($msg, 0);
        $ex->method = $this->method;
        $ex->params = $this->params;
        throw $ex;
    }
}