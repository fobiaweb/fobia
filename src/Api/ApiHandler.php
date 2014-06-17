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

    protected $mapMethods = array();

    function __construct()
    {
        $this->mapMethods = include __DIR__ . '/methodsmap.php';
    }

    public function request($method, $params)
    {
        $map = $this->mapMethods($method);

        $obj = new \Api\ApiMethod((array) $params);
        $obj->execute();
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
            if ( ! file_exists($file)) {
                $this->error('Не известный метом');
            }
            $r = include $file;
            if ($r !== null) {
                $this->response = $r;
            }
        } catch (\Api\Exception_BadRequest $ex) {
            $this->errors = $ex;
            return false;
        } catch (\Api\Exception_Halt $ex) {

        }

        return true;
    }
}