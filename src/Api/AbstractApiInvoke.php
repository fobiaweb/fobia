<?php

namespace Api;

/**
 * AbstractApiInvoke.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */
abstract class AbstractApiInvoke
{

    /**
     * @var array
     */
    protected $params;

    /**
     * @var array|mixed
     */
    protected $response;

    /**
     * @var string название метода
     */
    protected $method;

    /**
     * @var \Exception
     */
    private $exc;

    /**
     * @var \Api\ApiExpression
     */
    protected $exp;

    /**
     * @internal
     */
    public function __construct($params = null)
    {
        $this->params = (array) $params;
        
        $this->exp = new \Api\ApiExpression();
    }

    /**
     * @internal
     */
    public function __invoke()
    {
        $this->dispatchMethod('invike', func_get_args());
        return $this->response;
    }

    abstract protected function execute();

    /**
     * Выполнить метод
     *
     * @return boolean  флаг об успешности выполнения метода
     */
    public function invoke()
    {
        $this->exc      = null;
        $this->response = null;
        \Log::debug("API:: Вызов метода '$this->method' - ", $this->params);

        try {
            $this->dispatchMethod('execute', func_get_args());
            return true;
        } catch (\Api\Exception\Halt $exc) {
            return true;
        } catch (\Api\Exception\Error $exc) {
            $this->exc = $exc;
            \Log::error("API:: (" . get_class($exc) . ") " . $exc->getMessage());
            return false;
        } catch (\Exception $exc) {
            \Log::error("API:: (" . get_class($exc) . ") " . $exc->getMessage());
            return false;
        }
    }

    /**
     * Установка/получение параметра
     *
     * # // возвращает параметр
     * # params->params('name')
     *
     * # // устанавливает параметр
     * # params->params('name', 'value')
     *
     * # // обнуляет масив и устанавливает новые параметры
     * # params->params(array('name1', 'name2'))
     *
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

    protected function reset()
    {
        $this->response = null;
        $this->exc = null;
        
    }
    
    /**
     * Возвращает результат
     *
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }
    
    /**
     * Возвращает форматированный результат
     *
     * @return array
     */
    public function getFormatResponse()
    {
        if ($this->exc === null) {
            return array('response' => $this->response);
        } else {
            return array(
                'error' => $this->errorInfo()
            );
        }
    }

    /**
     * Информация об ошибки
     *
     * @return null|array
     */
    public function errorInfo()
    {
        if ($this->exc !== null) {
            return array(
                'err_msg'  => $this->exc->getMessage(),
                'err_code' => $this->exc->getCode(),
                'method'   => $this->method,
                'params'   => $this->params
            );
        } else {
            return null;
        }
    }

    /**
     * ХАК к вызову метода.
     * Аналог 'call_user_func_array', но работает быстрее
     *
     * @param string $method
     * @param array $p
     * @return mixed
     */
    protected function dispatchMethod($method, array $p = null)
    {
        $o = $this;
        switch (@count($p)) {
            case 0: return $o->{$method}();
            case 1: return $o->{$method}($p[0]);
            case 2: return $o->{$method}($p[0], $p[1]);
            case 3: return $o->{$method}($p[0], $p[1], $p[2]);
            case 4: return $o->{$method}($p[0], $p[1], $p[2], $p[3]);
            case 5: return $o->{$method}($p[0], $p[1], $p[2], $p[3], $p[4]);
            default: return call_user_func_array(array($o, $method), $p);
        }
    }

    /**
     * Завершение метода с ошибкой
     *
     * @param string $message
     * @throws \Exception
     */
    protected function error($message, $code = 1)
    {
        $exc = new \Api\Exception\Error($message, $code);
        throw $exc;
    }

    /**
     * Завершение и выход из метода выполнения
     *
     * @param type $data
     * @throws \Exception
     */
    protected function halt($data = null)
    {
        if ($data !== null) {
            $this->response = $data;
        } else {
            if ( ! $this->response) {
                $this->response = 1;
            }
        }
        throw new \Api\Exception\Halt();
    }

}