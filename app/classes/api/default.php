<?php

/**
 * {{name}}.php file
 *
 * Название метода
 * --------------------------------------------
 *
 * PARAMS:
 * ------
 *  offset      отступ, необходимый для получения определенного подмножества.
 *  count       количество записей, которые необходимо вернуть.
 *
 * --------------------------------------------
 *
 * RESULT
 * ------
 * Возвращаемый результат
 * --------------------------------------------
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 *
 * @api
 */
class Api_CLASSNAME extends AbstractApiInvoke
{

    protected $method = '{{name}}';

    protected function execute()
    {
        $p   = $this->params;
        $app = \App::instance();
        $db  = $app->db;

        // yeur code

        $this->response = 1;
    }
}
