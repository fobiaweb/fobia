<?php
/**
 * ApiMethod class  - ApiMethod.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Congress\Service;

/**
 * ApiMethod class
 *
 * @package   Congress\Service
 */
abstract class ApiMethod
{

    protected $params = array();
    protected $errors = array();

    public function __construct(array $params = array())
    {
        $this->params = $params;
    }

    /**
     * Метод
     * --------------------------------------------
     *
     * PARAMS:
     * ------
     * fields   (*) parametrs
     *
     * --------------------------------------------
     *
     * RESULT
     * ------
     * Result
     * --------------------------------------------
     *
     * @param  array $params
     * @return mixed
     * @api
     */
    public function _method()
    {
        $params = $this->prepare(func_get_args());
        extract($params);

    }

    /**
     * Установка/получение параметра
     * @param array|string $name
     * @param array|string $value
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

    /**
     * @return \Congress\Application
     */
    protected function getApp()
    {
        return \Congress\Application::getInstance();
    }

    /**
     * @return \ezcDbHandler
     */
    protected function getDb()
    {
        return $this->getApp()->db;
    }

    /**
     * @return array
     */
    public function errorInfo()
    {
        return $this->errors;
    }

    /**
     *
     * @param array $args
     * @return array
     */
    protected function prepare($args = null)
    {
        $this->errors = array();
        if ($args) {
            $params = $args[0];
        } else {
            $params = array();
        }

        return array_merge($this->params, $params);
    }

    /**
     *
     * @param \ezcQuerySelect|\ezcQueryUpdate|\ezcQueryDelete $q
     */
    protected function qPrepare($q)
    {
        $qArr = $q->qArr;
        if (isset($qArr['from'])) {
            foreach ($qArr['from'] as $from) {
                if ( ! $from) {
                    continue;
                }
                $q->from($from);
            }
        }

        if (isset($qArr['select'])) {
            foreach ($qArr['select'] as $select) {
                if ( ! $select) {
                    continue;
                }
                $q->select($select);
            }
        }

        $stmt = $q->prepare();
        return $stmt;
    }

    /**
     *
     * @param \ezcQuerySelect|\ezcQueryUpdate|\ezcQueryDelete $q
     * @param type $where
     * @param type $from
     */
    protected function qWhereFrom($q, $where, $from)
    {
        $q->where($where);
        $q->qArr['from'][] = $from;
    }

    /**
     *
     * @param \ezcQueryInsert|\ezcQueryUpdate $q
     * @param string $name
     * @param array  $params
     */
    protected function qSet($q, $name, $params)
    {
        if (array_key_exists($name, $params)) {
            $q->set($name, $this->getDb()->quote($params[$name]));
        }
        $q->where($where);
    }
}